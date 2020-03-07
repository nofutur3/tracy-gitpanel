<?php

namespace Nofutur3\GitPanel\Diagnostics;

use Tracy\IBarPanel;

/**
 * Tracy panel is displaying current branch and some more information,
 * inspired by Vojtěch Vondra - https://gist.github.com/vvondra/3645108.
 *
 * @author Jakub Vyvážil
 */
class Panel implements IBarPanel
{
    private $protectedBranches = [
        'master',
    ];

    /**
     * Panel constructor.
     *
     * @param array $protectedBranches
     */
    public function __construct(array $protectedBranches = [])
    {
        $this->protectedBranches = array_merge($this->protectedBranches, $protectedBranches);
    }

    /**
     * Renders HTML code for custom tab.
     *
     * @return string
     */
    public function getTab()
    {
        $style = $this->isProtectedBranch() ? 'background:#dd4742;color:white;padding:3px 4px 4px' : '';

        $icon =
            '<svg viewBox="10 10 512 512">
				<path fill="#f03c2e" d="M 502.34111,278.80364 278.79809,502.34216 c -12.86794,12.87712 -33.74784,12.87712 -46.63305,0 l -46.4152,-46.42448 58.88028,-58.88364 c 13.68647,4.62092 29.3794,1.51948 40.28378,-9.38732 10.97012,-10.9748 14.04307,-26.80288 9.30465,-40.537 l 56.75401,-56.74844 c 13.73383,4.73404 29.56829,1.67384 40.53842,-9.31156 15.32297,-15.3188 15.32297,-40.15196 0,-55.48356 -15.3341,-15.3322 -40.16175,-15.3322 -55.50254,0 -11.52454,11.53592 -14.37572,28.47172 -8.53182,42.6722 l -52.93386,52.93048 0,-139.28512 c 3.73267,-1.84996 7.25863,-4.31392 10.37114,-7.41756 15.32295,-15.3216 15.32295,-40.15196 0,-55.49696 -15.32296,-15.3166 -40.16844,-15.3166 -55.48025,0 -15.32296,15.345 -15.32296,40.17536 0,55.49696 3.78727,3.78288 8.17299,6.64472 12.85234,8.5604 l 0,140.57336 c -4.67935,1.91568 -9.05448,4.75356 -12.85234,8.56264 -11.60533,11.60168 -14.39801,28.6378 -8.4449,42.89232 L 162.93981,433.11336 9.6557406,279.83948 c -12.8743209,-12.88768 -12.8743209,-33.768 0,-46.64456 L 233.20978,9.65592 c 12.87017,-12.87456 33.74338,-12.87456 46.63305,0 l 222.49828,222.50316 c 12.87852,12.87876 12.87852,33.76968 0,46.64456"/>
			</svg>'
        ;

        $label = '<span class="tracy-label" style="'.$style.'">'.$this->getBranchName().'</span>';

        return $icon.$label;
    }

    /**
     * Renders HTML code for custom panel.
     *
     * @return string
     */
    public function getPanel()
    {
        $warning = '';
        $template = '<h1>GIT Status</h1><p style="color: #dd4742; font-weight: 500;">%s</p><div class=\"tracy-inner tracy-InfoPanel\"><table><tbody>%s</tbody></table></div>';
        $content = '';

        if ($this->isUnderVersionControl()) {
            if ($this->isProtectedBranch()) {
                $warning = 'The branch <strong>'.$this->getBranchName().'</strong> is protected. Beware!</p>';
            }

            // commit message
            if ($this->getLastCommitMessage()) {
                $content .= '<tr><td>Last commit</td><td> '.$this->getLastCommitMessage().' </td></tr>';
            }

            // heads
            if ($this->getHeads()) {
                $content .= '<tr><td>Branches</td><td> '.$this->getHeads().' </td></tr>';
            }

            // remotes
            if ($this->getRemotes()) {
                $content .= '<tr><td>Remotes</td><td> '.$this->getRemotes().' </td></tr>';
            }

            // tags
            if ($this->getTags()) {
                $content .= '<tr><td>Tags</td><td> '.$this->getTags().' </td></tr>';
            }
        } else {
            $content = '<p>This project looks unversioned. You may want to run <pre style="background: #000;color: #fff;margin-bottom: 5px;padding:3px">git init</pre> in root directory of the project.</p>';
        }

        return sprintf($template, $warning, $content);
    }

    protected function getBranchName()
    {
        $dir = $this->getDirectory();

        $head = $dir.'/.git/HEAD';
        if ($dir && is_readable($head)) {
            $branch = file_get_contents($head);
            if (0 === strpos($branch, 'ref:')) {
                $parts = explode('/', $branch, 3);

                return substr($parts[2], 0, -1);
            }

            return '('.substr($branch, 0, 7).'&hellip;)';
        }

        return 'NO GIT';
    }

    protected function getLastCommitMessage()
    {
        $dir = $this->getDirectory();

        $fileMessage = $dir.'/.git/COMMIT_EDITMSG';

        if ($dir && is_readable($fileMessage)) {
            $message = file_get_contents($fileMessage);

            return $message;
        }

        return null;
    }

    protected function getHeads()
    {
        $dir = $this->getDirectory();

        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir.'/.git/refs/heads', \FilesystemIterator::SKIP_DOTS));
        $iterator->rewind();
        while ($iterator->valid()) {
            $files[] = $iterator->getSubPathName();
            $iterator->next();
        }
        $message = '';

        if ($dir && is_array($files)) {
            $message .= '<ul style="list-style: none;">';
            foreach ($files as $file) {
                if ('.' !== $file && '..' !== $file) {
                    if ($file === $this->getBranchName()) {
                        $message .= '<li><strong>'.$file.'</strong></li>';
                    } else {
                        $message .= '<li>'.$file.'</li>';
                    }
                }
            }

            return $message.'</ul>';
        }

        return null;
    }

    protected function getRemotes()
    {
        $dir = $this->getDirectory();

        try {
            $files = scandir($dir.'/.git/refs/remotes');
        } catch (\ErrorException $e) {
            return null;
        }

        $message = '';

        if ($dir && is_array($files)) {
            foreach ($files as $file) {
                if ('.' !== $file && '..' !== $file) {
                    $message .= $file.' ';
                }
            }

            return $message;
        }

        return null;
    }

    protected function getTags()
    {
        $dir = $this->getDirectory();

        $files = scandir($dir.'/.git/refs/tags');
        $message = '';

        if ($dir && is_array($files)) {
            foreach ($files as $file) {
                if ('.' !== $file && '..' !== $file) {
                    $message .= $file.' ';
                }
            }

            return $message;
        }

        return null;
    }

    private function getDirectory()
    {
        $scriptPath = $_SERVER['SCRIPT_FILENAME'];

        $dir = realpath(dirname($scriptPath));
        while (false !== $dir && !is_dir($dir.'/.git')) {
            flush();
            $currentDir = $dir;
            $dir .= '/..';
            $dir = realpath($dir);

            // Stop recursion to parent on root directory
            if ($dir === $currentDir) {
                break;
            }
        }

        return $dir;
    }

    /**
     * Checks if the project is under version control.
     *
     * @return bool
     */
    private function isUnderVersionControl()
    {
        $dir = $this->getDirectory();
        $head = $dir.'/.git/HEAD';

        if ($dir && is_readable($head)) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the current branch is one of the protected branches (by default master only).
     *
     * @return bool
     */
    private function isProtectedBranch()
    {
        if (in_array($this->getBranchName(), $this->getProtectedBranches(), true)) {
            return true;
        }

        return false;
    }

    /**
     * Returns array of protected branches which will have red background to notify user
     * that there could be a problem.
     *
     * @return array
     */
    private function getProtectedBranches()
    {
        // todo: load from config if available
        return $this->protectedBranches;
    }
}

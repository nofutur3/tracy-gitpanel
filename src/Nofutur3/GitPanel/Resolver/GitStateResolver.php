<?php

namespace Nofutur3\GitPanel\Resolver;

use Nofutur3\GitPanel\Enum\File;
use Nofutur3\GitPanel\Enum\RefType;
use Nofutur3\GitPanel\Model\GitInformation;

class GitStateResolver
{
    private $dir = null;

    public function getInformation() {
        if ($this->isUnderVersionControl() === false) return false;

        return new GitInformation(
            $this->getBranchName(),
            $this->getLastCommitMessage(),
            $this->getHeads(),
            $this->getRemotes(),
            $this->getTags()
        );
    }

    private function getDirectory()
    {
        if ($this->dir !== null) return $this->dir;

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

        $this->dir = '/..'.$dir;

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

    private function getRefs($type)
    {
        $result = [];
        
        if (!file_exists($this->getDirectory().'/.git/refs/'.$type)) return $result;
        
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getDirectory().'/.git/refs/'.$type, \FilesystemIterator::SKIP_DOTS));
        $iterator->rewind();
        while ($iterator->valid()) {
            $result[] = $iterator->getSubPathName();
            $iterator->next();
        }

        return $result;
    }

    private function getFileContent($filename)
    {
        $file = $this->getDirectory() . '/.git/' . $filename;

        if (is_readable($file)) {
            return file_get_contents($file);
        }

        return null;
    }

    private function getRemotes()
    {
        return $this->getRefs(RefType::REMOTES);
    }

    private function getTags()
    {
        return $this->getRefs(RefType::TAGS);
    }

    private function getHeads() {
        return $this->getRefs(RefType::HEADS);
    }

    private function getLastCommitMessage()
    {
        return $this->getFileContent(File::COMMIT_MSG);
    }

    protected function getBranchName()
    {
        $branch = $this->getFileContent(File::HEAD);

        if (0 === strpos($branch, 'ref:')) {
            $parts = explode('/', $branch, 3);

            return substr($parts[2], 0, -1);
        }

        return '('.substr($branch, 0, 7).'&hellip;)';
    }
}
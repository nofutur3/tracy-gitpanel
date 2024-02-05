<?php

namespace Nofutur3\GitPanel\Diagnostics;

use Nofutur3\GitPanel\Resolver\GitStateResolver;
use Tracy\Helpers;
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
        'main',
    ];

    private $gitInformation;

    /**
     * Panel constructor.
     *
     * @param array $protectedBranches
     * @param bool $overrideDefaults
     */
    public function __construct(
        array $protectedBranches = [],
        $overrideDefaults = false
    ) {
        $this->protectedBranches = $overrideDefaults === false
            ? array_merge($this->protectedBranches, $protectedBranches)
            : $this->protectedBranches;

        $resolver = new GitStateResolver();
        $this->gitInformation = $resolver->getInformation();
    }

    /**
     * Renders HTML code for custom tab.
     *
     * @return string
     */
    public function getTab()
    {
        return Helpers::capture(function () {
            $data = $this->gitInformation;
            require __DIR__ . '/panels/git.tab.phtml';
        });
    }

    /**
     * Renders HTML code for custom panel.
     *
     * @return string
     */
    public function getPanel()
    {
        return Helpers::capture(function () {
            $data = $this->gitInformation;
            require __DIR__ . '/panels/git.panel.phtml';
        });
    }

    private function decorateBranches($input)
    {
        $result = '';

        foreach ($input as $branch) {
            if ($this->gitInformation->getCurrentBranch() === $branch) {
                $branch = '→ '.$branch;
                $result = '<li>'.$branch.'</li>' . $result;
            } else {
                $result .= '<li>'.$branch.'</li>';
            }
        }

        return $result;
    }

    /**
     * Determines if the current branch is one of the protected branches (by default master only).
     *
     * @return bool
     */
    private function isProtectedBranch()
    {
        return in_array($this->gitInformation->getCurrentBranch(), $this->getProtectedBranches(), true);
    }

    /**
     * Returns array of protected branches which will have red background to notify user
     * that there could be a problem.
     *  todo: remove this function
     * @return array
     */
    private function getProtectedBranches()
    {
        return $this->protectedBranches;
    }
}

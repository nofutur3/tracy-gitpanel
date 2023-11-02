<?php

namespace Nofutur3\GitPanel\Model;

class GitInformation
{
    private $currentBranch;

    private $commitMessage;

    private $branches;

    private $remotes;

    private $tags;

    /**
     * @param $currentBranch
     * @param $commitMessage
     * @param $branches
     * @param $remotes
     * @param $tags
     */
    public function __construct($currentBranch, $commitMessage, $branches, $remotes, $tags)
    {
        $this->currentBranch = $currentBranch;
        $this->commitMessage = $commitMessage;
        $this->branches = $branches;
        $this->remotes = $remotes;
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getCurrentBranch()
    {
        return $this->currentBranch;
    }

    /**
     * @return mixed
     */
    public function getCommitMessage()
    {
        return $this->commitMessage;
    }

    /**
     * @return mixed
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @return mixed
     */
    public function getRemotes()
    {
        return $this->remotes;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }
}
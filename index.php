<?php

/**
    * GitHubRepoSummary
    *
    * Create table summary of GitHub account repo statistics.
    *
    * @author        Martin Latter
    * @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link          https://github.com/Tinram/GitHub-Repo-Summary.git
*/


declare(strict_types=1);

###################################################
require('config.php');
require('classes/github_repo_summary.class.php');
###################################################

use Tinram\GitHubRepoSummary\GitHubRepoSummary;

?><!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1">
        <title>GitHub Repo Summary</title>
        <meta name="application-name" content="GitHubRepoSummary">
        <meta name="description" content="Create table summary of GitHub account repo statistics.">
        <meta name="author" content="Martin Latter">
        <link type="text/css" rel="stylesheet" href="css/ghrs.css">
    </head>

    <body>

    <?php
        $oGRS = new GitHubRepoSummary( new CONFIG() );
        echo $oGRS->output();
    ?>

    </body>

</html>
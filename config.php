<?php

declare(strict_types=1);

final class CONFIG
{
    /* GitHub API repo URL (or a local JSON file for testing) */
    const URL = 'https://api.github.com/users/<username>/repos?per_page=100';

    /* data array column to sort by */
    const SORT_BY = 'issues';

    /* log file filename */
    const LOG_FILE = 'ghrs.log';

    /* toggle account name display in log file */
    const LOG_GITHUB_ACCOUNT = true;

    /* browser string for cURL */
    const BROWSER = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0';
}

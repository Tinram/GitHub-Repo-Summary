<?php

declare(strict_types=1);

namespace Tinram\GitHubRepoSummary;


final class GitHubRepoSummary
{
    /**
        * GitHubRepoSummary
        *
        * Create table summary of GitHub account repo statistics.
        *
        * Coded to PHP 7.2
        *
        * @author        Martin Latter
        * @copyright     Martin Latter 05/07/2019
        * @version       0.05
        * @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
        * @link          https://github.com/Tinram/GitHub-Repo-Summary.git
    */


    /** @var array<int,mixed> $aResults, results holder */
    private $aResults = [];

    /** @var string $sOutput, output string container */
    private $sOutput = '';

    /** @var string $sSortBy, array column to sort by */
    private $sSortBy = '';

    /** @var string $sLogFile, log file container */
    private $sLogFile = '';

    /** @var boolean $bLogAccountName, GitHub Account URL for logging */
    private $bLogAccountName = true;

    /** @var string $sURL, url output */
    private $sURL = '';

    /** @var string $sAccountName, account name output */
    private $sAccountName = '';


    /**
        * Constructor.
        *
        * @param   CONFIG $oConfig, configuration object
    */

    public function __construct(object $oConfig = null)
    {
        if (is_null($oConfig))
        {
            die('No configuration object passed to ' . __METHOD__ . '() !');
        }
        else
        {
            $this->sSortBy = $oConfig::SORT_BY;
            $this->sLogFile = $oConfig::LOG_FILE;
            $this->bLogAccountName = $oConfig::LOG_GITHUB_ACCOUNT;
            $this->sURL = $oConfig::URL;

            # for GitHub accounts, not local files
            if (strpos($oConfig::URL, 'api.') !== false)
            {
                if  (preg_match('/users\/([\w_-]+)\/repos/', $oConfig::URL, $aMatch) === 1)
                {
                    $this->sAccountName = $aMatch[1];
                    $this->sURL = 'https://github.com/' . $this->sAccountName;
                }
            }

            $aRaw = $this->getData($oConfig::URL, $oConfig::BROWSER);
        }

        if (count($aRaw) !== 0)
        {
            $this->processData($aRaw);
        }
        else
        {
            die('Could not acquire repo data!');
        }

        $this->createOutput();
    }


    /**
        * Acquire GitHub API data on repo from cURL.
        *
        * @param   string $sURL, API URL
        * @param   string $sBrowser, browser string for cURL
        *
        * @return  array<int,array>
    */

    private function getData(string $sURL = '', string $sBrowser): array
    {
        $rCh = curl_init($sURL);
        curl_setopt($rCh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rCh, CURLOPT_USERAGENT, $sBrowser);
        $sRaw = curl_exec($rCh);
        curl_close($rCh);

        return json_decode($sRaw, true);
    }


    /**
        * Create sortable array from cURL data.
        *
        * @param   array<int,array> $aRaw, raw data from cURL
        *
        * @return  void
    */

    private function processData(array $aRaw): void
    {
        foreach ($aRaw as $aRepo)
        {
            $this->aResults[] = [ 'name' => $aRepo['name'], 'issues' => $aRepo['open_issues_count'], 'stars' => $aRepo['stargazers_count'], 'forks' => $aRepo['forks_count'], 'watchers' => $aRepo['watchers_count'] ];
        }

        usort($this->aResults, function (array $i1, array $i2): int {
            return $i2[$this->sSortBy] <=> $i1[$this->sSortBy]; # based on example by Mark Amery
        });

        //array_multisort(array_column($this->aResults, $this->sSortBy), SORT_DESC, $this->aResults);
    }


    /**
        * Create output table string.
        *
        * @return  void
    */

    private function createOutput(): void
    {
        $sFileStr = $this->bLogAccountName ? $this->sURL . PHP_EOL : '';
        $sFileStr .= 'repo | issues | stars | forks | watchers |' . PHP_EOL;

        $this->sOutput .= (($this->sAccountName !== '') ? '<h1>' . $this->sAccountName . '</h1>' : '');
        $this->sOutput .= '

            <table>
                <thead>
                    <tr>
                        <th id="repo">repo</th>
                        <th>issues</th>
                        <th>stars</th>
                        <th>forks</th>
                        <th>watchers</th></tr>
                </thead>
                <tbody>';

        foreach($this->aResults as $aRepo)
        {
            $this->sOutput .= '
                    <tr>
                        <td class="repo">' . $aRepo['name'] . '</td>
                        <td>' . $aRepo['issues'] . '</td>
                        <td>' . $aRepo['stars'] . '</td>
                        <td>' . $aRepo['forks'] . '</td>
                        <td>' . $aRepo['watchers'] . '</td>
                    </tr>';

            $sFileStr .= $aRepo['name'] . ' | ' . $aRepo['issues'] . ' | ' . $aRepo['stars'] . ' | ' . $aRepo['forks'] . ' | ' . $aRepo['watchers'] . ' |'  . PHP_EOL;
        }

        $this->sOutput .= '
                </tbody>
            </table>';

        $this->logWrite($sFileStr);
    }


    /**
        * Log data to file for historical comparison.
        *
        * @param   string $sMessage, message to log
        *
        * @return  void
    */

    private function logWrite(string $sMessage = ''): void
    {
        if ( ! file_exists($this->sLogFile))
        {
            touch($this->sLogFile);
        }

        $sMessage = PHP_EOL . $sMessage . date('Y-m-d H:i:s P T') . PHP_EOL;
        $iLogWrite = file_put_contents($this->sLogFile, $sMessage, FILE_APPEND);

        if ($iLogWrite === false)
        {
            echo 'Could not write to logfile ' . $this->sLogFile . PHP_EOL;
        }
    }


    /**
        * Getter for data output.
        *
        * @return  string
    */

    public function output(): string
    {
        return $this->sOutput;
    }
}

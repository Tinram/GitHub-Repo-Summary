
# GitHub Repo Summary

#### Create a table summary of a GitHub account's repo statistics.


## Background

I often miss 'issues' when they are posted in my repos.  
This script provides a concise overview of my repos, sorting by 'issues' in the first table column.

Or viewing Rasmus' GitHub account:

[1]: https://tinram.github.io/images/ghrs.png
![GitHub-Repo-Summary][1]


## Usage

1. Clone the repository or extract the file archive into a suitable directory in the server's web directory.
2. On *nix servers, set appropriate file attributes for the directories and files
    + e.g. a directory called *ghrs* on Debian-based distros:  
    `sudo chown -R userx:www-data ghrs/ && chmod 770 ghrs/ ghrs/css ghrs/classes && chmod 660 ghrs/ghrs.log`
3. Edit */config.php*: change `<username>` in line 9 to a real GitHub account name.
4. View */index.php* in a browser.


## License

GitHub Repo Summary is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).

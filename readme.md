# Bandcamp YAML

A tool to be used with the [hsmusic.wiki](https://github.com/hsmusic) project. Currently Bandcamp's API doesn't give us the endpoints we need so we're scraping the HTML pages and parsing the embedded JSON with phpQuery.

## How to
Paste the full URL into the input field, and then hit "Fetch". Depending on how big the album is, you may have to wait for the app to fetch all relevant track info. It will appear in a grey box which you can then copy to your clipboard with the "Copy to clipboard" button.

## How to repo
Runs on PHP back-end with no dependency manager, so you will simply have to start a PHP server in the root directory (e.g. [XAMPP](https://www.apachefriends.org/download.html)). This might change in the future if the tool gets more complex.
Sulu Documentation
==================

The [Sulu](http://sulu.io) Documentation can be found on (http://docs.sulu.io).


Contributing
------------

If there's something missing or wrong, feel free to contribute or contact us.
We are on Twitter [@sulu](https://twitter.com/sulu) and we got a 
[Slack Channel](http://sulu.io/en/contact).


Build Documentation Locally
---------------------------

If you are planning to create a pull request to this documentation repository
you may want to check if your changes are valid. You can achieve that by using
the `make` tool.

### Prerequisites

Make sure you have Python 3.12 or higher installed. For more details about Sphinx,
see the [official Sphinx installation guide][1].

### Setup with Virtual Environment (Recommended)

We recommend using a virtual environment to install dependencies:

**Bash/Zsh:**
```bash
# Create virtual environment
python3 -m venv venv

# Activate virtual environment
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
```

**Fish Shell:**
```fish
# Create virtual environment
python3 -m venv venv

# Activate virtual environment
source venv/bin/activate.fish

# Install dependencies
pip install -r requirements.txt
```

### Build

Start the building process by executing:

    make html

After that you can check the result in the `_build/html` folder.

### Test Locally

To test the documentation in a browser, open the `_build/html/index.html` file.

### Clean Build

To remove all built files:

    make clean


[1]: https://www.sphinx-doc.org/en/master/usage/installation.html

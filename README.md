# shopier-payment-system
# Offer a balance loading system to your websites with Shopier.
# Favicon settings can be made via the settings file.
# Database settings can be made from the "connect" file.
# The system performs database operations for the user's balance transactions. In the following files, you need to set the table and column names according to your system.
# "-index.php"
# "-return_url_page.php"
# Make edits to the following files to be able to connect with Shopier.
# "-index.php > line 20 and line 21"
# "-.env > line 1 and line 2"
# For security reasons, the amount of the balance to be loaded is kept on SQL for a short time. For the system to work smoothly, you need to add the following code to the table containing user data.
# "ALTER TABLE `table_name` ADD `postbalance` INT(15) NOT NULL DEFAULT '0';"

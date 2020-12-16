-- Admin
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'ZeFdQTBFxcrFmDGe';
GRANT ALL PRIVILEGES ON *.* TO 'administrator'@'%' IDENTIFIED BY PASSWORD '*2E63A4048F4073C9208797D301D53E4ADC4D0083' WITH GRANT OPTION;


-- Klant
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'klantgebruiker';
GRANT USAGE ON *.* TO 'klant'@'%' IDENTIFIED BY PASSWORD '*19DA82EA8AA9D99C4A5D093BBE3D2320A92CF37D';

GRANT INSERT ON `nerdygadgets`.`privateorder` TO 'klant'@'%';

GRANT SELECT, UPDATE (LastEditedWhen, QuantityOnHand, LastEditedBy) ON `nerdygadgets`.`stockitemholdings` TO 'klant'@'%';

GRANT SELECT ON `nerdygadgets`.`stockitemimages` TO 'klant'@'%';

GRANT SELECT ON `nerdygadgets`.`stockgroups` TO 'klant'@'%';

GRANT INSERT ON `nerdygadgets`.`privatecustomers` TO 'klant'@'%';

GRANT INSERT ON `nerdygadgets`.`privateorderlines` TO 'klant'@'%';

GRANT SELECT ON `nerdygadgets`.`stockitemstockgroups` TO 'klant'@'%';

GRANT SELECT (ColorID, StockItemName, InternalComments, Barcode, Brand, SupplierID, IsChillerStock, StockItemID, MarketingComments, QuantityPerOuter, Tags, Size, UnitPrice, OuterPackageID, LastEditedBy, TaxRate, TypicalWeightPerUnit, RecommendedRetailPrice, LeadTimeDays, CustomFields, SearchDetails, UnitPackageID) ON `nerdygadgets`.`stockitems` TO 'klant'@'%';

GRANT SELECT ON `nerdygadgets`.`discount` TO 'klant'@'%';

GRANT SELECT (Surname, AccountID, City, Email, StreetNumber, Infix, PostalCode, Street, Gender, Firstname), INSERT ON `nerdygadgets`.`account` TO 'klant'@'%';


-- Pi
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'temperatuursensor';
GRANT USAGE ON *.* TO 'pi'@'%' IDENTIFIED BY PASSWORD '*D5B47A9BC2F185C98632FCB1B2B7EE796A13967A';

GRANT INSERT, SELECT ON `nerdygadgets`.`coldroomtemperatures_archive` TO 'pi'@'%';

GRANT INSERT (ColdRoomTemperatureID), UPDATE ON `nerdygadgets`.`coldroomtemperatures` TO 'pi'@'%';
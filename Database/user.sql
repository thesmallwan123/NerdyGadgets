-- Pi
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'temperatuursensor';
GRANT USAGE ON *.* TO 'pi'@'%' IDENTIFIED BY PASSWORD '*D5B47A9BC2F185C98632FCB1B2B7EE796A13967A';

GRANT INSERT, SELECT ON `nerdygadgets`.`coldroomtemperatures_archive` TO 'pi'@'%';

GRANT INSERT (ColdRoomTemperatureID), UPDATE ON `nerdygadgets`.`coldroomtemperatures` TO 'pi'@'%';


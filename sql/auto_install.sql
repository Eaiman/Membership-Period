-- /*******************************************************
-- *
-- * Create civicrm_membershipperiod
-- *
-- * Developed for interview purpose
-- *
-- * @author Eaiman Shoshi
-- *
-- *******************************************************/
CREATE TABLE IF NOT EXISTS `civicrm_membershipperiod` (
     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique Membership Period ID',
     `start_date` date NOT NULL   COMMENT 'Membership Period start date',
     `end_date` date NOT NULL   COMMENT 'Membership Period end date',
     `renewed_date` datetime NOT NULL   COMMENT 'Membership renewed date',
     `membership_id` int unsigned NOT NULL   COMMENT 'FK to membership',
     `contribution_id` int unsigned   COMMENT 'FK to contribution',
      PRIMARY KEY (`id`),
      CONSTRAINT FK_civicrm_membershipperiod_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership`(`id`) ON DELETE CASCADE,
      CONSTRAINT FK_civicrm_membershipperiod_contribution_id FOREIGN KEY (`contribution_id`) REFERENCES `civicrm_contribution`(`id`) ON DELETE SET NULL
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
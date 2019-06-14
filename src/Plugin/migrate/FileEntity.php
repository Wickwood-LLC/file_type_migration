<?php

namespace Drupal\file_type_migration\Plugin\migrate;

use Drupal\migrate\Exception\RequirementsException;
use Drupal\migrate_drupal\Plugin\migrate\FieldMigration;

/**
 * Plugin class for Drupal 7 file entity migrations dealing with fields.
 */
class FileEntity extends FieldMigration {

  /**
   * {@inheritdoc}
   */
  public function getProcess() {
    if (!$this->init) {
      $this->init = TRUE;
      $this->fieldDiscovery->addEntityFieldProcesses($this, 'file');

      // $definition = [
      //   // 'source' => [
      //   //   'plugin' => 'profile_field',
      //   //   'ignore_map' => TRUE,
      //   // ],
      //   'idMap' => [
      //     'plugin' => 'null',
      //   ],
      //   'destination' => [
      //     'plugin' => 'null',
      //   ],
      // ];
      // try {
      //   $profile_migration = $this->migrationPluginManager->createStubMigration($definition);
      //   // Ensure that Profile is enabled in the source DB.
      //   $profile_migration->checkRequirements();
      //   foreach ($profile_migration->getSourcePlugin() as $row) {
      //     $name = $row->getSourceProperty('name');
      //     $this->process[$name] = $name;
      //   }
      // }
      // catch (RequirementsException $e) {
      //   // The checkRequirements() call will fail when the profile module does
      //   // not exist on the source site.
      // }
    }
    return parent::getProcess();
  }

}

<?php

namespace Drupal\file_type_migration\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 file entity source from database.
 *
 * @MigrateSource(
 *   id = "d7_file_entity",
 *   source_module = "file_entity"
 * )
 */
class FileEntity extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('file_managed', 'f')
      ->fields('f');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'fid' => $this->t('File ID'),
      'uid' => $this->t('The user.uid of the user who is associated with the file.'),
      'filename' => $this->t('Name of the file with no path components. This may differ from the basename of the URI if the file is renamed to avoid overwriting an existing file.'),
      'uri' => $this->t('The URI to access the file (either local or remote).'),
      'filemime' => $this->t('The file’s MIME type.'),
      'filesize' => $this->t('The size of the file in bytes.'),
      'status' => $this->t('A field indicating the status of the file. Two status are defined in core: temporary (0) and permanent (1).'),
      'timestamp' => $this->t('UNIX timestamp for when the file was added.'),
      'type' => $this->t('Language'),
      'origname' => $this->t('Original name of the file with no path components. Used by the filefield_paths module.'),
      'uuid' => $this->t('The Universally Unique Identifier.'),
    ];

    // // Profile fields.
    // if ($this->moduleExists('profile')) {
    //   $fields += $this->select('profile_fields', 'pf')
    //     ->fields('pf', ['name', 'title'])
    //     ->execute()
    //     ->fetchAllKeyed();
    // }

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $uid = $row->getSourceProperty('fid');

    // $roles = $this->select('users_roles', 'ur')
    //   ->fields('ur', ['rid'])
    //   ->condition('ur.uid', $uid)
    //   ->execute()
    //   ->fetchCol();
    // $row->setSourceProperty('roles', $roles);

    // $row->setSourceProperty('data', unserialize($row->getSourceProperty('data')));

    // // If this entity was translated using Entity Translation, we need to get
    // // its source language to get the field values in the right language.
    // // The translations will be migrated by the d7_user_entity_translation
    // // migration.
    // $entity_translatable = $this->isEntityTranslatable('user');
    // $source_language = $this->getEntityTranslationSourceLanguage('user', $uid);
    // $language = $entity_translatable && $source_language ? $source_language : $row->getSourceProperty('language');
    // $row->setSourceProperty('entity_language', $language);

    // Get Field API field values.
    foreach ($this->getFields('file') as $field_name => $field) {
      // Ensure we're using the right language if the entity and the field are
      // translatable.
      // $field_language = $entity_translatable && $field['translatable'] ? $language : NULL;
      $field_language = NULL;
      $row->setSourceProperty($field_name, $this->getFieldValues('file', $field_name, $uid, NULL, $field_language));
    }

    // Get profile field values. This code is lifted directly from the D6
    // ProfileFieldValues plugin.
    // if ($this->getDatabase()->schema()->tableExists('profile_value')) {
    //   $query = $this->select('profile_value', 'pv')
    //     ->fields('pv', ['fid', 'value']);
    //   $query->leftJoin('profile_field', 'pf', 'pf.fid=pv.fid');
    //   $query->fields('pf', ['name', 'type']);
    //   $query->condition('uid', $row->getSourceProperty('uid'));
    //   $results = $query->execute();

    //   foreach ($results as $profile_value) {
    //     if ($profile_value['type'] == 'date') {
    //       $date = unserialize($profile_value['value']);
    //       $date = date('Y-m-d', mktime(0, 0, 0, $date['month'], $date['day'], $date['year']));
    //       $row->setSourceProperty($profile_value['name'], ['value' => $date]);
    //     }
    //     elseif ($profile_value['type'] == 'list') {
    //       // Explode by newline and comma.
    //       $row->setSourceProperty($profile_value['name'], preg_split("/[\r\n,]+/", $profile_value['value']));
    //     }
    //     else {
    //       $row->setSourceProperty($profile_value['name'], [$profile_value['value']]);
    //     }
    //   }
    // }

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'fid' => [
        'type' => 'integer',
        'alias' => 'f',
      ],
    ];
  }

}

  /**
   * Allows to change configure some fields of the response, based on the
   * generator.yml configuration file. Supported configuration directives are
   * "date_format" and "tag_name"
   *
   * @return  void
   */
  protected function configureFields()
  {
<?php
$fields = $this->configuration->getValue('default.fields');
$specific_configuration_directives = false;

foreach ($fields as $field => $configuration)
{
  if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type']))
  {
    $specific_configuration_directives = true;
    continue;
  }
}
?>
<?php if ($specific_configuration_directives): ?>
    foreach ($this->objects as $i => $object)
    {
<?php foreach ($fields as $field => $configuration): ?>
<?php if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type'])): ?>
      if (isset($object['<?php echo $field ?>']))
      {
<?php if (isset($configuration['date_format'])): ?>
        $object['<?php echo $field ?>'] = date('<?php echo $configuration['date_format'] ?>', strtotime($object['<?php echo $field ?>']));
<?php endif; ?>
<?php if (isset($configuration['type'])): ?>
	     $object['<?php echo $field ?>'] = is_null($object['<?php echo $field ?>']) ? null : (<?php echo $configuration['type'] ?>) $object['<?php echo $field ?>'];
<?php endif; ?>
<?php if (isset($configuration['tag_name'])): ?>
      }
      if (array_key_exists('<?php echo $field ?>', $object))
      {
        $object['<?php echo $configuration['tag_name'] ?>'] = $object['<?php echo $field ?>'];
        unset($object['<?php echo $field ?>']);
<?php endif; ?>
      }
<?php endif; ?>
<?php endforeach; ?>

      $this->objects[$i] = $object;
    }
<?php endif; ?>
  }

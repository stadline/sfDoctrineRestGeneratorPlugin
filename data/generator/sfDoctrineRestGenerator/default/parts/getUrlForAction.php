  /**
  * Generates the url to an other action of the module
  * @param string $action the action to which a url must gbe generated
  *                       (index|show|delete|create)
  * @param bool $absolute whether or not to generate an absolute url
  * @return string the generated url
  **/
  protected function getUrlForAction($action = 'show', $absolute = true)
  {
    $route_name = sfContext::getInstance()->getRouting()->getCurrentRouteName();
    if (preg_match('#_'.$this->getActionName().'$#', $route_name))
    {
      $route_name = substr($route_name, 0, strrpos($route_name, '_'.$this->getActionName()));
    }

    if ($action == 'show')
    {
      $route_parameters = array_merge(
        $this->getRoute()->getParameters(),
        $this->object->identifier()
      );
    }
    else
    {
      $route_parameters = $this->getRoute()->getParameters();
    }

    return $this->getController()->genUrl(
      array_merge(
        $route_parameters,
        array(
          'sf_route' => $route_name.'_'.$action,
          'sf_format' => $this->getFormat(),
        )
      ),
      $absolute
    );
  }

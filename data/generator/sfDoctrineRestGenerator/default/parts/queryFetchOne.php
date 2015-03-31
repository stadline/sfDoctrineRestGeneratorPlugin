  /**
   * Execute the query for selecting an object, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function queryFetchOne($params)
  {
    $this->objects = $this->dispatcher->filter(
      new sfEvent(
        $this,
        'sfDoctrineRestGenerator.filter_result',
        array()
      ),
      array($this->query($params)->limit(1)->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY))
    )->getReturnValue();
  }

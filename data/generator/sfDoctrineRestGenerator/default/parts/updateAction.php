  /**
   * Updates a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::PUT));
    $content = $request->getContent();

    // Restores backward compatibility. Content can be the HTTP request full body, or a form encoded "content" var.
    if (strpos($content, 'content=') === 0 || $request->hasParameter('content'))
    {
      $content = $request->getParameter('content');
    }

    $request->setRequestFormat('html');

    try
    {
      $this->validateUpdate($content);
    }
    catch (Exception $e)
    {
      $this->getResponse()->setStatusCode(406);
      $serializer = $this->getSerializer();
      $this->getResponse()->setContentType($serializer->getContentType());
      $error = $e->getMessage();

      // event filter to enable customisation of the error message.
      $result = $this->dispatcher->filter(
        new sfEvent($this, 'sfDoctrineRestGenerator.filter_error_output'),
        $error
      )->getReturnValue();

      if ($error === $result)
      {
        $error = array(array('message' => $error));
        $this->output = $serializer->serialize($error, 'error');
      }
      else
      {
        $this->output = $serializer->serialize($result);
      }

      $this->setTemplate('index');
      return sfView::SUCCESS;
    }

    // retrieve the object
<?php $primaryKey = Doctrine_Core::getTable($this->getModelClass())->getIdentifier() ?>
    $primaryKey = $request->getParameter('<?php echo $primaryKey ?>');
    $this->object = $this->query(array('<?php echo $primaryKey ?>', $primaryKey))->limit(1)->fetchOne();
    $this->forward404Unless($this->object);

    // update and save it
    $this->updateObjectFromRequest($content);

    $this->getResponse()->setStatusCode(204);
    return $this->doSave();
  }

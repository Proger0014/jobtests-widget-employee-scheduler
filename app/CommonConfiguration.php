<?php

namespace app;

use yii\base\BaseObject;

class CommonConfiguration extends BaseObject {
  public $projectUrlCreator;

  public function __construct($config = []) {
    parent::__construct($config);
  }

  public function init() {
    parent::init();
  }
}
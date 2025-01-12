<?php

namespace app\controllers;

use yii\web\Controller;

class ExampleController extends Controller {
  public function actionIndex() {
    return $this->render('index');
  }
}
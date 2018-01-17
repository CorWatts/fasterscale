<?php
namespace common\components;

use yii;
use Amenadiel\JpGraph\Graph as JpGraph;
use Amenadiel\JpGraph\Plot;

/**
 * Graph is a collection of functions related to the score chart that is used
 * in the email report and when a user makes their check-in scores public
 */
class Graph extends \yii\base\BaseObject {
  private $user;

  public function __construct(\common\interfaces\UserInterface $user, $config = []) {
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * Returns the filepath location of the generated graph image
   *
   * @return string the graph image filepath
   */
  public function getFilepath() {
    $path = Yii::getAlias('@graphImgPath');
    $filename = $this->user->getIdHash() . ".png";
    return $path. '/' . $filename;
  }

  /**
   * Returns the URL of the generated graph image
   *
   * @return string the graph image URL
   */
  public function getUrl() {
    $filename = $this->user->getIdHash() . ".png";
    return \yii\helpers\Url::to("@graphImgUrl/$filename", true);
  }

  /**
   * Deletes the graph image
   */
  public function destroy() {
    $filepath = $this->getFilepath();
    @unlink($filepath);
  }

  /**
   * Creates the graph image
   *
   * Generates the graph image according to the values passed in by $values. It
   * always returns the in-memory image, saving the image to disk is optionally
   * specified with the $save boolean.
   *
   * @param array $values an associative array of dates => scores
   * @param bool $save used to specify whether or not to save the generated image to disk at the filepath returned by getFilepath(). Defaults to false.
   * @return string the encoded image
   */
  public function create(array $values, bool $save = false) {
    if($save) {
      // wipe out the current image, if it exists
      $this->destroy();
    }

    $scores = array_values($values);
    $dates = array_map(function($date) {
      return (new \DateTime($date))->format('M j, Y');
    }, array_keys($values));

    $graph = new JpGraph\Graph(800, 600);
    $graph->SetImgFormat('png');
    $graph->img->SetImgFormat('png');
    $graph->img->SetMargin(60, 60, 40, 140);
    $graph->img->SetAntiAliasing();
    $graph->SetScale("textlin");
    $graph->yaxis->scale->SetAutoMin(0);
    $graph->yaxis->SetLabelFormatCallback('floor');
    $graph->SetShadow();
    $graph->title->Set("Last month's scores");
    $graph->title->SetFont(FF_ARIAL, FS_BOLD, 20);
    $graph->xaxis->SetLabelAngle(45);
    $graph->xaxis->SetTickLabels($dates);
    $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 15);
    $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 15);
    $p1 = new Plot\LinePlot($scores);
    $p1->SetColor("#37b98f");
    $p1->SetFillColor("#92d1b5");
    $p1->mark->SetWidth(8);
    $p1->SetCenter();
    $graph->Add($p1);
    $img = $graph->Stroke(_IMG_HANDLER);

    ob_start();
    imagepng($img);
    $img_data = ob_get_clean();

    if($save) {
      $filepath = $this->getFilepath(); 
      if(!is_dir(dirname($filepath))) {
        mkdir(dirname($filepath), 0766, true);
      }

      file_put_contents($filepath, $img_data, LOCK_EX);
    }

    return $img_data;
  }
}

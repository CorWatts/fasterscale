<?php
namespace common\components;

use yii;
use Amenadiel\JpGraph\Graph as JpGraph;
use Amenadiel\JpGraph\Plot\Plot;
use Amenadiel\JpGraph\Plot\AccBarPlot;
use Amenadiel\JpGraph\Plot\GroupBarPlot;
use Amenadiel\JpGraph\Plot\BarPlot;

/**
 * Graph is a collection of functions related to the behaviors chart that is used
 * in the email report and when a user makes their check-in behaviors public
 */
class Graph extends \yii\base\BaseObject
{
    private $user;

    public function __construct(\common\interfaces\UserInterface $user, $config = [])
    {
        $this->user = $user;
        parent::__construct($config);
    }

    /**
     * Returns the filepath location of the generated graph image
     *
     * @return string the graph image filepath
     */
    public function getFilepath()
    {
        $path = Yii::getAlias('@graphImgPath');
        $filename = $this->user->getIdHash() . ".png";
        return $path. '/' . $filename;
    }

    /**
     * Returns the URL of the generated graph image
     *
     * @return string the graph image URL
     */
    public function getUrl()
    {
        $filename = $this->user->getIdHash() . ".png";
        return \yii\helpers\Url::to("@graphImgUrl/$filename", true);
    }

    /**
     * Deletes the graph image
     */
    public function destroy()
    {
        $filepath = $this->getFilepath();
        @unlink($filepath);
    }

    /**
     * Creates the graph image
     *
     * Generates the graph image according to the values passed in by $values. It
     * always returns the in-memory image, saving the image to disk is optionally
     * specified with the $toDisk boolean.
     *
     * @param array $values an associative array of dates => check-in summary
     * @param bool $toDisk used to specify whether or not to save the generated image to disk at the filepath returned by getFilepath(). Defaults to false.
     * @return string the encoded image
     */
    public function create(array $checkins, bool $toDisk = false)
    {
        if ($toDisk) {
            // wipe out the current image, if it exists
            $this->destroy();
        }

        // Create the graph
        $graph = new JpGraph\Graph(800, 600, 'auto');
        $graph->title->Set("Last 30 Days of Selected Behaviors");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 20);
        $graph->SetImgFormat('png');
        $graph->img->SetImgFormat('png');
        $graph->SetScale("textlin");
        $graph->img->SetMargin(60, 60, 40, 140);
        $graph->img->SetAntiAliasing();

        $graph->SetShadow();
        $graph->ygrid->SetFill(false);

        // Setup dates as labels on the X-axis
        $graph->xaxis->SetTickLabels(array_keys($checkins));
        $graph->xaxis->HideTicks(false, false);

        $graph->yaxis->scale->SetAutoMin(0);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);
        $graph->xaxis->SetLabelAngle(45);
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 10);
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 15);


        // format the data into something nicer
        $accum = [];
        foreach ($checkins as $checkin_sum) {
            for ($i = 1; $i <= 7; $i ++) {
                $accum[$i][] = array_key_exists($i, $checkin_sum) ? $checkin_sum[$i]['count'] : 0;
            }
        }

        // Create the bar plots
        $plots = [];
        $category = Yii::$container->get(\common\interfaces\CategoryInterface::class);
        foreach ($accum as $category_key => $category_data) {
            $bplot = new BarPlot($category_data);
            $color = $category::$colors[$category_key]['color'];

            $bplot->SetColor($color);
            $bplot->SetFillColor($color);
            $bplot->SetLegend(($category::getCategories())[$category_key]);

            $plots[] = $bplot;
        }

        $gbbplot = new AccBarPlot($plots);
        $graph->Add($gbbplot);

        $graph->legend->SetColumns(3);
        $graph->legend->SetPos(0.5, 0.98, 'center', 'bottom'); // position it at the center, just above the bottom edge

        $img = $graph->Stroke(_IMG_HANDLER);

        ob_start();
        imagepng($img);
        $img_data = ob_get_clean();

        if ($toDisk) {
            $filepath = $this->getFilepath();
            if (!is_dir(dirname($filepath))) {
                mkdir(dirname($filepath), 0766, true);
            }

            file_put_contents($filepath, $img_data, LOCK_EX);
        }

        return $img_data;
    }
}

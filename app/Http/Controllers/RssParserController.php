<?php

namespace App\Http\Controllers;

use DOMElement;
use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Interfaces\IRssToNewsBlockConvertable;
use Closure;
use Hamcrest\Arrays\IsArray;
use \Symfony\Component\DomCrawler\Crawler;

class RssParserController extends Controller
{
    /**
    * Метод запроса
    */
    private string $RequestMethod = 'GET';

    /**
    * Время ожидания ответа веб сервера
    */
    private int $TimeOut = 60;

    /**
     * Конвертер обрабатывающий данные
     */
    private IRssToNewsBlockConvertable $Converter;

    /**
     * Текущий массив для ставнения по тагу с key value
     */
    private array $CompareTagArray;

    /**
     * Результрирующий массив данных для каждого тега тебующего стравнения
     */
    private array $EachResultArray;

    /**
     * Массив данных для преобразования в конвертере
     */
    private array $ToConverterData;

    /**
     * Название аттрибута обрабатываемого в текущее время
     */
    private string $CurrentAttributeName;

    /**
     * Аддрес запроса
     */
    private string $Url;

    /**
     * название тега в RSS внутри которого новость
     */
    private string $NewsTagName;

    /**
     * Массив по умолчанию для работы с данными конвертера
     */
    private array $DefaultConverterArray;

    public function __construct(IRssToNewsBlockConvertable $converter)
    {
        $this->Converter=$converter;

        $data = $converter->getRequestData();

        $this->Url = $data['url'];
        $this->NewsTagName = $data['news_tag_name'];
        $this->DefaultConverterArray = $data['converter_array'];
    }

    /**
     * Сканирование дочерних элементов RSS
     */
    public function ScanChildrenElement(Crawler $node, int $id)
    {
        if($this->CompareTagArray['value']== $node->attr($this->CompareTagArray['key'])){
            array_push($this->EachResultArray,$node->attr($this->CurrentAttributeName));
        }
    }

    /**
    * Сканирование элементов RSS выбранных по значимому тегу
    */
    public function ScanElement(Crawler $node, int $id)
    {

        foreach ($this->ToConverterData as $key => $value) {

            $this->EachResultArray = array();

            try {

                if(is_array($value) && isset($value['tag_name']) && isset($value['attr_name'])){

                    $this->CurrentAttributeName=$value['attr_name'];

                    if(isset($value['compare_attr']) && is_array($value['compare_attr']) &&
                       isset($value['compare_attr']['key']) && isset($value['compare_attr']['value'])){

                        $this->CompareTagArray=$value['compare_attr'];

                        $node->children($value['tag_name'])->each(function(Crawler $node, int $id){
                            $this->ScanChildrenElement($node,$id);
                        });

                    }
                    else{
                        array_push($this->EachResultArray,$node->children($value['tag_name'])->attr($value['attr_name']));
                    }

                    $this->ToConverterData[$key]=$this->EachResultArray;
                }
                else{
                    $this->ToConverterData[$key]=$node->children($value)->text();
                }

            } catch (\Throwable $e) {
                unset($this->ToConverterData[$key]);
                continue;
            }

        }

    }

    /**
     * Запуск процедуры скрапинга
     */
    public function StartScrapping(): void
    {

        $client = new Client(HttpClient::create(['timeout' => $this->TimeOut]));

        $data = [
            'request_method' => $this->RequestMethod,
            'request_url' => $this->Url,
            'responce_http_code' => 0,
            'responce_body' => 'nocontent'
        ];

        try {

            $rssreader = $client->request($this->RequestMethod, $this->Url);

            $data['responce_http_code'] = $client->getInternalResponse()->getStatusCode()??0;
            $data['responce_body'] = $client->getInternalResponse()->getContent()??'';

            if($data['responce_body'] == '' || $data['responce_http_code'] == 0){
                throw new \Exception('Нет данных для обработки');
            }

            $rssreader->filter($this->NewsTagName)->each(function (Crawler $node, int $id)  {

                $this->ToConverterData = $this->DefaultConverterArray;

                $this->ScanElement($node,$id);

                $this->Converter->makeConvert($this->ToConverterData);

            });

        } catch (\Throwable $th) {
            $data['responce_body']="request-exception";
        }

        WebRequestLogController::saveRequestData($data);

    }

}

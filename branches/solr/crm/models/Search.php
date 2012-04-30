<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Search
{
	public $solrClient;
	public static $facetFields = array(
		'ticket'=>array(
			'status','client_id','label','type','department_id'
		)
	);

	public function __construct()
	{
		$this->solrClient = new SolrClient(array(
			'hostname'=> SOLR_SERVER_HOSTNAME,
			'port'    => SOLR_SERVER_PORT,
			'path'    => SOLR_SERVER_PATH
		));
	}

	/**
	 * @param array $_GET
	 * @return SolrObject
	 */
	public function query($get)
	{
		$query = new SolrQuery('*:*');
		$query->setFacet(true);
		foreach (self::$facetFields['ticket'] as $field) {
			$query->addFacetField($field);
		}
		$solrResponse = $this->solrClient->query($query);
		return $solrResponse->getResponse();
	}

	/**
	 * @param SolrObject $object
	 * @return array An array of CRM models based on the search results
	 */
	public static function hydrateDocs(SolrObject $object)
	{
		$models = array();
		if (isset($object->response->docs)) {
			foreach ($object->response->docs as $doc) {
				switch ($doc->recordType) {
					case 'ticket':
						$models[] = new Ticket($doc->id);
						break;
				}
			}
		}
		return $models;
	}

	/**
	 * Indexes a single record in Solr
	 *
	 * @param mixed $record
	 */
	public function add($record)
	{
		$document = $this->createDocument($record);
		$this->solrClient->addDocument($document);
	}

	/**
	 * Indexes a whole collection of records in Solr
	 *
	 * @param mixed $list
	 */
	public function index($list)
	{
		foreach ($list as $record) {
			$this->add($record);
		}
	}

	/**
	 * Prepares a Solr Document with the correct fields for the record type
	 *
	 * @param mixed $record
	 * @return SolrInputDocument
	 */
	private function createDocument($record)
	{
		if ($record instanceof Ticket) {
			$document = new SolrInputDocument();
			$document->addField('recordType', 'ticket');
			$document->addField('id', (string)$record->getId());
			if ($record->getLatLong()) {
				$document->addField('coordinates', $record->getLatLong());
			}

			$stringFields = array(
				'status','resolution','client_id',
				'location','city','state','zip'
			);
			foreach ($stringFields as $field) {
				$get = 'get'.ucfirst($field);
				if ($record->$get()) {
					$document->addField($field, $record->$get());
				}
			}

			$personFields = array('enteredBy','assigned','referred');
			foreach ($personFields as $field) {
				$get = 'get'.ucfirst($field).'Person';
				$person = $record->$get();
				if ($person) {
					$document->addField($field.'Person_id',(string)$person->getId());
					if ($field == 'assigned' && $person->getDepartment_id()) {
						$document->addField('department_id', $person->getDepartment_id());
					}
				}
			}

			$issueFields = array('type','contactMethod');
			$description = '';
			foreach ($record->getIssues() as $issue) {
				$description.= $issue->getDescription();
				foreach ($issueFields as $field) {
					$get = 'get'.ucfirst($field);
					$document->addField($field, $issue->$get());
				}
				foreach ($issue->getLabels() as $label) {
					$document->addField('label', $label);
				}
			}
			if ($description) {
				$document->addField('description', $description);
			}

			foreach (AddressService::$customFieldDescriptions as $field=>$def) {
				$value = $record->get($field);
				if ($value) {
					$document->addField($field, $value);
				}
			}

			return $document;
		}
	}
}
<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 *
 */
abstract class MongoResultIterator implements Iterator,Countable
{
	protected $mongo;
	protected $cursor;

	abstract public function find($fields=null,$order=null);
	abstract public function loadResult($key);

	/**
	 * Creates an empty collection
	 */
	public function __construct()
	{
		$this->mongo = Database::getConnection();
	}

	/**
	 * Applies an ordering to the results
	 *
	 * If you want to apply an order, you can call this function after
	 * calling find(), but before you start iterating
	 *
	 * @param array $order
	 */
	public function order($order)
	{
		$this->cursor->order($order);
	}

	/**
	 * Applies a limit to the results
	 *
	 * If you want to apply a limit, you can call this function after
	 * calling find(), but before you start iterating.
	 *
	 * @param int $limit
	 */
	public function limit($limit)
	{
		$this->cursor->limit($limit);
	}

	/**
	 * @return array
	 */
	public function getExplain()
	{
		return $this->cursor->explain();
	}

	/**
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @return Zend_Paginator
	 */
	public function getPaginator($itemsPerPage,$currentPage=1)
	{
		$paginator = new Zend_Paginator(new MongoPaginatorAdapter($this->cursor,$this));
		$paginator->setItemCountPerPage($itemsPerPage);
		$paginator->setCurrentPageNumber($currentPage);
		return $paginator;
	}

	// SPLIterator Section
	/**
	 * Reset the pionter to the first element
	 */
	public function rewind() {
		$this->cursor->rewind();
	}
	/**
	 * Advance to the next element
	 */
	public function next() {
		$this->cursor->next();
	}
	/**
	 * Return the index of the current element
	 * @return int
	 */
	public function key() {
		return $this->cursor->key();
	}
	/**
	 * @return boolean
	 */
	public function valid() {
		return $this->cursor->valid();
	}
	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->loadResult($this->cursor->current());
	}

	/**
	 * @return Iterator
	 */
	public function getIterator()
	{
		return $this;
	}

	// Countable Section
	/**
	 * @return int
	 */
	public function count()
	{
		return $this->cursor->count();
	}
}
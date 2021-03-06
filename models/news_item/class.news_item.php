<?

class news_item extends Model {

	public function construct() {
		// get the fname and lname of the mod__person_id
		$r = aql::value("person{fname,lname}",$this->mod__person_id);
		$this->addProperty('modified_by');
		$this->modified_by = $r['fname'] . ' ' . $r['lname'];
		$this->addProperty('message');
		$this->message = $this->getMessage();
	}

	public function getLayoutPath() {
		return sprintf('lib/news/layouts/%s.php', $this->category);
	}

	public function getMessage() {
		$json = json_decode($this->json);
		ob_start();
		include $this->getLayoutPath();
		$message = ob_get_contents();
		ob_end_clean();
		return $message;
	}

}
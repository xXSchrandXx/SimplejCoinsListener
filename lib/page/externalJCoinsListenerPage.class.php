<?php
namespace wcf\page;
use wcf\system\WCF;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;

  class externalJCoinsListenerPage extends AbstractPage {

    protected string $savedkey = EXTERNAL_SECRET_KEY;# <-- Hier die config option

    protected string $message;
    private bool $error;

    protected string $secretkey;
    protected int $userID;
    protected int $amount;
    protected int $authorID;
    protected string $authorname;
    protected int $moderative;
    protected string $reason;

    public function readParameters() {
      parent::readParameters();
      if (isset($_GET['secretkey'])) $this->secretkey = $_GET['secretkey'];
      if (isset($_GET['userID'])) $this->userID = $_GET['userID'];
      if (isset($_GET['amount'])) $this->amount = $_GET['amount'];
      if (isset($_GET['authorID'])) $this->authorID = $_GET['authorID'];
      if (isset($_GET['authorname'])) $this->authorname = $_GET['authorname'];
      if (isset($_GET['moderative'])) $this->moderative = $_GET['moderative'];
      if (isset($_GET['reason'])) $this->reason = $_GET['reason'];
    }

    public function readData() {
      parent::readData();
      $this->error = false;
      if (empty($this->secretkey)) {
        $this->secretkey = 'EMPTY';
        $this->error = true;
      }
      if (empty($this->userID)) {
        $this->userID = 0;
        $this->error = true;
      }
      if (empty($this->amount)) {
        $this->amount = 0;
        $this->error = true;
      }
      if (empty($this->authorID)) {
        $this->authorID = 0;
        $this->error = true;
      }
      if (empty($this->authorname)) {
        $this->authorname = 'EMPTY';
        $this->error = true;
      }
      if (empty($this->moderative)) {
        $this->moderative = 0;
        $this->error = true;
      }
      if (empty($this->reason)) {
        $this->reason = 'EMPTY';
        $this->error = true;
      }
      if ($this->error) {
        $this->message =
        "ERROR While parsing Data.;
        secretkey: '$this->secretkey';
        amount: '$this->amount';
        reason: '$this->reason';
        authorID: '$this->authorID';
        authorname: '$this->authorname';
        userID: '$this->userID';
        moderative: '$this->moderative'";
      }
      else if ($this->secretkey != $this->savedkey) {
        $this->message =
        "ERROR With the given secretkey.
        secretkey is not valid.";
      }
      else {
        UserJCoinsStatementHandler::getInstance()->create('de.wcflabs.jcoins.statement.transfer', null, [
          'amount' => $this->amount,
          'reason' => $this->reason,
          'author' => $this->authorID,
          'authorname' => $this->authorname,
          'userID' => $this->userID,
          'moderative' => $this->moderative,
      ]);
          $this->message = 
          "Successfully added/removed '$this->amount' from '$this->secretkey's jcoins.;
          secretkey: '$this->secretkey';
          amount: '$this->amount';
          reason: '$this->reason';
          authorID: '$this->authorID';
          authorname: '$this->authorname';
          userID: '$this->userID';
          moderative: '$this->moderative'";
      }
    }

    public function assignVariables() {
      parent::assignVariables();
      WCF::getTPL()->assign([
#        'secretkey' => $this->secretkey,
#        'userID' => $this->userID,
#        'amount' => $this->amount,
#        'authorID' => $this->authorID,
#        'authorname' => $this->authorname,
#        'reason' => $this->reason,
		'message' => $this->message
      ]);
    }

  }

?>
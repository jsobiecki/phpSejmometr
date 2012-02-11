<?php

namespace Sejmometr;


class Deputy extends Entity {
  var $deputy_id = NULL;

  function __construct($deputy_id) {
    $this->deputy_id = $deputy_id;
  }

  public static function retrieve(array $deputy_ids = NULL) {
    if ($deputy_ids == NULL) {
      $deputy_ids = Entity::request("poslowie");
    }

    return Entity::retrieve('Sejmometr\\Deputy', $deputy_ids);
  }

  public function getInfo() {
    if (isset($this->cache['info'])) {
      return $this->cache['info'];
    }
    else {
      $data = Entity::request("posel/{$this->deputy_id}/info");

      if (is_object($data)) {
        $this->cache['info'] = (array) $data;
        return $this->cache['info'];
      }
    }
  }

  public function getSessions() {
    $sessions_ids = Entity::request("posel/{$this->deputy_id}/wystapienia");
    return Sessions::retrieve($sessions_ids);
  }


  public function getVotings() {
    $voting_ids = Entity::request("posel/{$this->deputy_id}/glosowania");
    return Voting::retrieve($votings_ids);
  }


  public function getCommitties() {
    $voting_ids = Entity::request("posel/{$this->deputy_id}/komisje");
    return Committies::retrieve($votings_ids);
  }

  public function getFinancialDeclarations() {
    $voting_ids = Entity::request("posel/{$this->deputy_id}/komisje");
    return Document::retrieve($votings_ids);
  }


}

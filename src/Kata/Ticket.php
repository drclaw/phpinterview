<?php

namespace Kata;

/**
 * Class Movie.
 */
class Ticket {

  /**
   * The age (in years) of the person buying the ticket.
   * @var int
   */
  protected $age;

  /**
   * Is this ticket for a student?
   * @var int
   */
  protected $isStudent;

  /**
   * Age discount cutoffs
   */
  const CHILD_PRICE_CUTOFF = 13;
  const SENIOR_PRICE_CUTOFF = 64;

  /**
   * Ticket Prices
   */
  const PRICE_GENERAL = 11.0;
  const PRICE_STUDENT = 8.0;
  const PRICE_SENIOR_OR_GROUP = 6.0;
  const PRICE_CHILD = 5.5;


  /**
   * Constructs a Ticket object
   */
  public function __construct($age, $isStudent) {
    $this->age = $age;
    $this->isStudent = $isStudent;
  }

  /**
   * Returns the ticket price for the ticket.
   *
   * @param boolean $group
   *   Whether or not this ticket is for a group.
   */
  public function getPrice($group) {
    if ($this->age < self::CHILD_PRICE_CUTOFF) {
      return self::PRICE_CHILD;
    }
    elseif ($group || $this->age > self::SENIOR_PRICE_CUTOFF) {
      return self::PRICE_SENIOR_OR_GROUP;
    }
    elseif ($this->isStudent) {
      return self::PRICE_STUDENT;
    }
    return self::PRICE_GENERAL;
  }

}

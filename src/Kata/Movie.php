<?php

namespace Kata;
use Kata\DaysOfWeek;
use Kata\Ticket;

/**
 * Class Movie.
 */
class Movie {

  /**
   * The number of tickets to be purchased.
   *
   * @var array
   */
  protected $tickets = [];

  /**
   * The runtime fo the movie.
   * @var int
   */
  protected $runtime;

  /**
   * The day the movie is on.
   * @var int
   */
  protected $day;

  /**
   * Whether the purchase if for parquet (main auditorium) or loge (balcony).
   * @var boolean
   */
  protected $isParquet;

  /**
   * Whether or not the movie is 3D.
   * @var boolean
   */
  protected $is3D;

  /**
   * Surcharge and discounts
   */
  const SURCHARGE_3D = 3.0;
  const SURCHARGE_OVERLENGTH = 1.5;
  const SURCHARGE_WEEKEND = 1.5;
  const SURCHARGE_LOGE = 2.0;
  const DISCOUNT_MOVIE_DAY = 2.0;

  /**
   * Amount of time after which a movie is considered overlength
   */
  const REGULAR_MOVIE_LENGTH = 120;

  /**
   * Begins a transaction.
   *
   * @param int $runtime
   *   The minutes of the user.
   * @param int $day
   *   The integer value from DaysOfWeek constants.
   * @param bool $isParquet
   *   Is either parquet or lode.
   * @param bool $is3D
   *   Is the film in 3D or 2D.
   */
  public function startPurchase($runtime, $day, $isParquet, $is3D) {
    $this->tickets = [];
    $this->runtime = $runtime;
    $this->day = $day;
    $this->isParquet = $isParquet;
    $this->is3D = $is3D;
  }

  /**
   * Adds a ticket to the transaction.
   *
   * @param int $age
   *   The age of the ticket holder.
   * @param bool $isStudent
   *   Whether the ticket is for a student.
   */
  public function addTicket($age, $isStudent) {
    $this->tickets[] = new Ticket($age, $isStudent);
  }

  /**
   * Get the final price.
   *
   * @return float
   *   The total price of the transaction.
   */
  public function finishPurchase() {

    $total = 0;

    foreach ($this->tickets as $ticket) {
      $total += $ticket->getPrice($this->hasGroupDiscount());
    }

    // Apply pricing rules
    $this->apply3DSurcharge($total);
    $this->applyOverlengthSurcharge($total);
    $this->applyWeekendSurcharge($total);
    $this->applyLogeSurcharge($total);
    $this->applyMovieDayDiscount($total);

    return $total;

  }

  /**
   * Determines if the purchase gets a group discount.
   */
  public function hasGroupDiscount() {
    return count($this->tickets) >= 20;
  }

  /**
   * Is the purchase for a weekend?
   */
  public function isWeekend() {
    return $this->day == DaysOfWeek::SAT || $this->day == DaysOfWeek::SUN;
  }

  /**
   * Applies the 3D surcharge to an amount if necessary.
   */
  public function apply3DSurcharge(&$amount) {
    if ($this->is3D) {
      $amount += self::SURCHARGE_3D * count($this->tickets);
    }
  }

  /**
   * Applies the Overlength surcharge to an amount if necessary.
   */
  public function applyOverlengthSurcharge(&$amount) {
    if ($this->runtime > self::REGULAR_MOVIE_LENGTH) {
      $amount += self::SURCHARGE_OVERLENGTH * count($this->tickets);
    }
  }

  /**
   * Applies the Weekend surcharge to an amount if necessary.
   */
  public function applyWeekendSurcharge(&$amount) {
    if ($this->isWeekend()) {
      $amount += self::SURCHARGE_WEEKEND * count($this->tickets);
    }
  }

  /**
   * Applies the Loge* surcharge to an amount if necessary.
   */
  public function applyLogeSurcharge(&$amount) {
    if (!$this->isParquet) {
      $amount += self::SURCHARGE_LOGE * count($this->tickets);
    }
  }

  /**
   * Applies the Movie Day discount to an amount if necessary.
   */
  public function applyMovieDayDiscount(&$amount) {
    if (!$this->hasGroupDiscount() && $this->day == DaysOfWeek::THU) {
      $amount -= self::DISCOUNT_MOVIE_DAY * count($this->tickets);
    }
  }



}

<?php


class Tribe__Tickets_Plus__Commerce__Stock_Counter extends Tribe__Tickets_Plus__Commerce__Abstract_Ticket_Total_Provider implements Tribe__Tickets_Plus__Commerce__Total_Provider_Interface {

	/**
	 * Gets the sum of all the stock for all the tickets associated to an event.
	 *
	 * @param int|string|WP_Post $event Either an event post `ID` or a `WP_Post` instance for an event.
	 */
	public function get_total_for( $event ) {
		$event                = get_post( $event );
		$supported_post_types = Tribe__Tickets__Main::instance()->post_types();
		if ( empty( $event ) || ! in_array( $event->post_type, $supported_post_types ) ) {
			return new WP_Error( 'not-an-event', sprintf( 'The post with ID "%s" is not an event.', $event->ID ) );
		}

		$sum = 0;

		$all_tickets = Tribe__Tickets__Tickets::get_all_event_tickets( $event->ID );
		/** @var Tribe__Tickets__Ticket_Object $ticket */
		foreach ( $all_tickets as $ticket ) {
			$sum += $ticket->stock();
		}

		// return the sum
		return $sum;
	}
}
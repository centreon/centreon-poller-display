Feature: Configuration export
    As a Centreon Web user
    I want to export my monitoring configuration from my central to a poller display
    So that I can manage all my pollers from a central place

    Background:
        Given a central Centreon server and a poller with Poller Display
        And I am logged in the central server

    Scenario: Export configuration
        Given hosts linked to the poller
        And services linked to the poller
        When I export the poller configuration
        Then the hosts monitoring data is available from the central
        And the services monitoring data is available from the central
        And the hosts monitoring data is available from the poller
        And the services monitoring data is available from the poller

<?php

namespace madpilot78\bottg\tests\DBTraits

trait SQLite
{
    /**
     * Create in memory sqlite DB for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Cleanup in memory DB.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Assert row count equal to expected.
     *
     * @param string $table
     * @param int $expected
     * @param string $message
     *
     * @return void
     */
    public function assertTableRowCount(string $table, int $expected, string $message = ''): void
    {
    }

    /**
     * Assert row exists.
     *
     * @param string $table
     * @param array $data
     * @param string $message
     *
     * @return void
     */
    public function assertTableHasRow(string $table, array $data, string $message = ''): void
    {
    }

    /**
     * Assert row absent
     *
     * @param string $table
     * @param array $data
     * @param string $message
     *
     * @return void
     */
    public function assertTableMissingRow(string $table, array $data, string $message = ''): void
    {
    }
}

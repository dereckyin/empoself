<?php

namespace Empoself\Tests;

require_once '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Empoself\App\Consult;

class ConsultTest extends TestCase {
    private $mockDb;
    private $consult;

    protected function setUp(): void {
        // Create a mock for the mysqli class
        $this->mockDb = $this->getMockBuilder(mysqli::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        // Create a mock for the Database class
        $databaseMock = $this->getMockBuilder(Database::class)
                             ->disableOriginalConstructor()
                             ->getMock();
        $databaseMock->conn = $this->mockDb;

        // Instantiate the Consult class with the mocked database
        $this->consult = new Consult($databaseMock);
    }

    public function testInsertSuccess() {
        // Prepare the mock to expect a prepare call and return a mock statement
        $stmtMock = $this->getMockBuilder(mysqli_stmt::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->mockDb->expects($this->once())
                     ->method('prepare')
                     ->willReturn($stmtMock);

        // Prepare the statement to expect a bind_param call and an execute call
        $stmtMock->expects($this->once())
                 ->method('bind_param')
                 ->with(
                     $this->anything(), // We can ignore the exact parameters for this test
                     $this->anything()
                 );

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Simulate a successful execution

        // Prepare test data
        $data = [
            'name' => 'John Doe',
            'gender' => 'Male',
            'birthday' => '1990-01-01',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
            'emergency_contact' => 'Jane Doe',
            'emergency_contact_phone' => '0987654321',
            'emergency_contact_relation' => 'Sister',
            'referral_source' => 'Friend',
            'health_condition' => 'None',
            'account_status' => 'Active',
            'profile_photo_url' => 'http://example.com/photo.jpg',
            'active_branch' => 'Main',
            'id_number' => 'ID123456',
            'password_hash' => 'hashed_password',
            'contact_time' => 'Evening',
            'fitness_goals' => json_encode(['Lose Weight']),
            'referrer_id' => 'Referrer123',
            'height' => '180',
            'weight' => '75',
            'default_invoice_type' => 'Electronic',
            'mobile_barcode' => 'barcode123',
            'company_tax_id' => 'TAX123456',
            'company_name' => 'Example Corp'
        ];

        // Call the insert method
        $result = $this->consult->insert($data);

        // Assert the result
        $this->assertEquals("New record created successfully", $result);
    }

    public function testInsertFailure() {
        // Prepare the mock to expect a prepare call and return a mock statement
        $stmtMock = $this->getMockBuilder(mysqli_stmt::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->mockDb->expects($this->once())
                     ->method('prepare')
                     ->willReturn($stmtMock);

        // Prepare the statement to expect a bind_param call and an execute call
        $stmtMock->expects($this->once())
                 ->method('bind_param')
                 ->with(
                     $this->anything(), // We can ignore the exact parameters for this test
                     $this->anything()
                 );

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(false); // Simulate a failed execution

        // Call the insert method
        $result = $this->consult->insert([]);

        // Assert the result
        $this->assertStringContainsString("Error:", $result);
    }
}
?> 
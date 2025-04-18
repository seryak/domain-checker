<?php

namespace Tests\Unit\Service;

use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use App\Service\WhoisService;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Modules\Tld\TldInfo;
use Iodev\Whois\Whois;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class WhoisServiceTest extends TestCase
{
    private WhoisService $service;
    private MockInterface $whoisMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->whoisMock = Mockery::mock(Whois::class);
        $this->service = new WhoisService();
        
        // Replace the real Whois instance with our mock
        $reflection = new \ReflectionClass($this->service);
        $property = $reflection->getProperty('whois');
        $property->setAccessible(true);
        $property->setValue($this->service, $this->whoisMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_gets_domain_info_successfully()
    {
        $domain = new Domain(['name' => 'example.com']);
        $tldInfo = Mockery::mock(TldInfo::class);
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andReturn($tldInfo);

        $result = $this->service->getDomainInfo($domain);
        
        $this->assertSame($tldInfo, $result);
    }

    /** @test */
    public function it_throws_exception_for_empty_domain()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Domain is empty');
        
        $domain = new Domain(['name' => '']);
        $this->service->getDomainInfo($domain);
    }

    /** @test */
    public function it_handles_connection_errors()
    {
        $domain = new Domain(['name' => 'example.com']);
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andThrow(ConnectionException::class);

        $result = $this->service->getDomainInfo($domain);
        
        $this->assertNull($result);
        $this->assertEquals(DomainStatus::ERROR->value, $domain->status);
    }

    /** @test */
    public function it_handles_whois_errors()
    {
        $domain = new Domain(['name' => 'example.com']);
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andThrow(WhoisException::class);

        $result = $this->service->getDomainInfo($domain);
        
        $this->assertNull($result);
        $this->assertEquals(DomainStatus::ERROR->value, $domain->status);
    }

    /** @test */
    public function it_handles_server_mismatch_errors()
    {
        $domain = new Domain(['name' => 'example.com']);
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andThrow(ServerMismatchException::class);

        $result = $this->service->getDomainInfo($domain);
        
        $this->assertNull($result);
        $this->assertEquals(DomainStatus::ERROR->value, $domain->status);
    }

    /** @test */
    public function it_updates_status_to_expired_when_domain_expired()
    {
        $domain = new Domain(['name' => 'example.com']);
        $tldInfo = Mockery::mock(TldInfo::class);
        $tldInfo->expirationDate = '2020-01-01'; // Past date
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andReturn($tldInfo);

        $this->service->checkDomain($domain);
        
        $this->assertEquals(DomainStatus::EXPIRED->value, $domain->status);
    }

    /** @test */
    public function it_updates_status_to_ok_when_domain_active()
    {
        $domain = new Domain(['name' => 'example.com']);
        $tldInfo = Mockery::mock(TldInfo::class);
        $futureDate = now()->addYear()->format('Y-m-d');
        $tldInfo->expirationDate = $futureDate;
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andReturn($tldInfo);

        $this->service->checkDomain($domain);
        
        $this->assertEquals(DomainStatus::OK->value, $domain->status);
        $this->assertEquals($futureDate, $domain->expired_at->format('Y-m-d'));
    }

    /** @test */
    public function it_does_nothing_when_domain_info_not_available()
    {
        $domain = new Domain(['name' => 'example.com']);
        
        $this->whoisMock->shouldReceive('loadDomainInfo')
            ->with('example.com')
            ->once()
            ->andReturn(null);

        $this->service->checkDomain($domain);
        
        $this->assertNull($domain->status);
    }

    /** @test */
    public function it_handles_invalid_domain_formats()
    {
        $invalidDomains = [
            'example..com',
            '-example.com',
            'example-.com',
            'example.com-',
            str_repeat('a', 256) . '.com',
            'example.123',
            '@example.com'
        ];

        foreach ($invalidDomains as $domainName) {
            $domain = new Domain(['name' => $domainName]);
            
            $this->whoisMock->shouldReceive('loadDomainInfo')
                ->with($domainName)
                ->once()
                ->andThrow(WhoisException::class);

            $result = $this->service->getDomainInfo($domain);
            
            $this->assertNull($result);
            $this->assertEquals(DomainStatus::ERROR->value, $domain->status);
        }
    }
}
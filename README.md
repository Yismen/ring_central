 # Dainsys Ring Central
 Extends ring central reports functionality. 
  ### Installation
 1. Require using composer: `composer require dainsys/ring_central`.
 2. Add DB Connection values in your .env file:
```
RC_DB_HOST=
RC_DB_DATABASE=
RC_DB_USERNAME=
RC_DB_PASSWORD=
RC_DB_DRIVER=
```
 3. Run the migrations: `php artisan migrate`
#### Ussage
 1. Make sure your commands extednds the `\Dainsys\RingCentral\Console\Commands\AbstractProductionReportCommand`. Alternatively, you may use the `rc:make-command` command to create your reports.
 2. Your signature property must provide a required attribute for `dates`, which is required to run the reports. If none passed, today's date will be assumed.
 3. This package uses `dainsys/mailing` package under the hood [More details](https://github.com/Yismen/mailing). Or, make sure to provide implementation for the `recipients` method. 
    1. Using `dainsys/mailing` package:
       1. Visit url `/mailing/admin/mailables` in your app and create a mailable record with the class name of the command. For current example, `App\Console\Commands\PublishingProductionReport`.
       2. Visit url `/mailing/admin/recipients` to create new recipients associate them with the created mailable report.
    2. Providing your oun implementation in your report commands:
       ```js 
        protected function recipients(): array
        {
            return [];
        }
       ```
 4. Provide implementation to all abstract methods required. Use the following code as an example.
 ```js
<?php

namespace App\Console\Commands;

use Dainsys\RingCentral\Console\Commands\ProductionReportCommand;

class PublishingProductionReport extends ProductionReportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publishing:production-report 
    {dates?} Range of dates between the data will be queried. Exc: 2023-01-01 or 2023-01-01,2023-01-02. Today\'s date will be assumed if not passed! 
    ';

    /**
     * List of dialGroups to query. Provide all dialGroups.
     *
     * @return array
     */
    public function dialGroups(): array 
    {
        // return ['PUB%'];
    }

    /**
     * List of teams to query.
     *
     * @return array
     */
    public function teams(): array 
    {
        // return ['ECC%'];
    }
            
	/**
	 * Email subject
	 */
	public function subject(): string 
    {
        return str($this->name)->replace(':', ' ')->headline();
	}
}
 ```
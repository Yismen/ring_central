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
#### Ussage
 1. Make sure your commands extednds the `\Dainsys\RingCentral\Console\Commands\AbstractProductionReportCommand`.
 2. Your signature property must provide a required option for --dates property, which is required to run the reports
 3. Provide implementation to all abstract methods required. Use the following code as an example.
 ```js
<?php

namespace App\Console\Commands;

use Dainsys\RingCentral\Console\Commands\AbstractProductionReportCommand;

class PublishingProductionReport extends AbstractProductionReportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publishing:production-report 
        {--d|dates=} Required. Range of dates between the data will be queried. Example: 2023-01-01 or 2023-01-01,2023-01-02 or 2023-01-01|2023-01-02
        ';
        
    protected $description = 'Send a production report for a given period';

    public function handle()
    {
       return parent::manage(new \Dainsys\RingCentral\Reports\ProductionReportByDates()); //reports are grouped by date, and add a date field
       // parent::manage(new \Dainsys\RingCentral\Reports\ProductionReportSummarized()); // will add date_from and date_to fields. Not grouped by date
    }

    /**
     * Subject to be used on the email.
     */
    public function subject(): string
    {
        return 'Publishing Production Report';
    }

    public function dialGroupPrefixes(): array
    {
        return ['PUB%'];
    }

    public function teams(): array
    {
        return ['ECC%'];
    }

    public function dialGroups(): array
    {
        return ['%'];
    }
}
 ```

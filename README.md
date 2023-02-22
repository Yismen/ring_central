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
 2. Your signature property must provide a required option for --dates property, which is required to run the reports.
 3. This package uses `dainsys/mailing` package under the hood [More details](https://github.com/Yismen/mailing). So, to run your reports you will need:
    1. Visit url `/mailing/admin/mailables` in your app and create a mailable record with the class name of the command. For current example, `App\Console\Commands\PublishingProductionReport`.
    2. Visit url `/mailing/admin/recipients` to create new recipients associate them with the created mailable report.
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
            return ['PUB%'];
        }
	
        /**
         * List of teams to query.
         *
         * @return array
         */
        public function teams(): array 
        {
            return ['ECC%'];
        }
	
        /**
         * @return array
         */
        public function sheets(): array 
        {
            return [                    
                \Dainsys\RingCentral\Exports\Sheets\ProductionSheet::class ,
                \Dainsys\RingCentral\Exports\Sheets\CallsSheet::class,
                \Dainsys\RingCentral\Exports\Sheets\ContactsSheet::class,
            ];
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

![Jokes Card](https://readme-jokes.vercel.app/api)
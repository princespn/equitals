<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [

		Commands\OptimizedBinaryQualify::class,
		Commands\OptimizedBinaryIncome::class,
		Commands\OptimizedRoiStatic::class,
		Commands\AutoSendCron::class,
		Commands\ConfirmTransactionCron::class,
		Commands\StatisticsCron::class,
		Commands\AssignMatchingBonusIncomeCron::class,
		Commands\MatchingBonusIncomeCron::class,
		Commands\DailyReport::class,
		Commands\SignUpMail::class,
		Commands\SecurityCrossVerifyTopup::class,
		Commands\SecurityCrossVerifyWithdraw::class,
		Commands\SecuritySendFakeTopupMail::class,
		Commands\SecuritySendFakeWithdrawMail::class,

	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {

		$schedule->command('cron:optimized_binary_qualify')->timezone('Asia/Kolkata')->withoutOverlapping()->everyFifteenMinutes();
		
		$schedule->command('cron:optimized_binary_qualify')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:30');
		
		$schedule->command('cron:optimized_binary_income')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:35');
		$schedule->command('cron:optimized_roi_static')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:40');
		
		$schedule->command('cron:assign_matching_bonus')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:45');
		
		$schedule->command('cron:matching_bonus')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:50');
		
		$schedule->command('cron:dailyreport')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('05:55');
		
		$schedule->command('cron:confirm_transaction')->withoutOverlapping()->everyFifteenMinutes();

		$schedule->command('cron:sign_up_mail')->withoutOverlapping()->everyMinute();

		$schedule->command('cron:auto_withdraw_send')->withoutOverlapping()->everyFiveMinutes();
		
		$schedule->command('cron:cross_verify_topup')->withoutOverlapping()->everyFiveMinutes();
		$schedule->command('cron:cross_verify_withdraw')->withoutOverlapping()->everyFiveMinutes();
		$schedule->command('cron:fake_topup_mail')->withoutOverlapping()->everyFiveMinutes();
		$schedule->command('cron:fake_withdraw_mail')->withoutOverlapping()->everyFiveMinutes();
		$schedule->command('cron:pass_bv_upline')->withoutOverlapping()->everyFifteenMinutes();

		$schedule->command('cron:statistics')->timezone('Asia/Kolkata')->withoutOverlapping()->dailyAt('10:00');

	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */

	protected function commands() {
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}

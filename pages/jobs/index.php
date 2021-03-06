<?php

if(path(2)) {
  if(path(2) == 'type') {
    $types = $fields['employment']['options'];
    $jobs = $sqlite->select('jobs', [
      'employment' => path(3)
    ]);

    define('ROUTE', [
      ['type', array_keys($types)]
    ]);
  } else {
    $job = $sqlite->select('jobs', [
      'id' => path(2)
    ])[0];

    define('ROUTE', [
      ["{$job['id']}"]
    ]);
  }
} else {
  $jobs = $sqlite->select('jobs');
}

if($oauth->connected) {
  $user = $oauth->request('https://slack.com/api/users.identity')->user;
}

?>

<?php if(!$oauth->connected) { ?>
  <a href="<?= $oauth->slack(env('SLACK_ID'), env('SLACK_SECRET')); ?>">Sign in with Slack</a>
<?php } else { ?>
  <p>Welcome, <?= $user->name; ?>!</p>
<?php } ?>

<?php if(path(2) && path(2) != 'type') { ?>
  <h1><a href="<?= path('/jobs/'); ?>">Jobs</a></h1>
  <h2><?= $job['title']; ?><?= $if($job['company'], null, ' (%s)'); ?></h2>
  <p><?= $job['description']; ?></p>
  <ul>
    <li>Location: <?= $job['location']; ?></li>
    <?php if($job['employment']) { ?>
      <li>Employment: <?= $job['employment']; ?></li>
    <?php } ?>
    <?php if($job['experience']) { ?>
      <li>Experience: <?= $job['experience']; ?></li>
    <?php } ?>
    <?php if($job['website']) { ?>
      <li>Website: <?= $job['website']; ?></li>
    <?php } ?>
  </ul>
<?php } else { ?>
  <h1><?= $if(path(3), $types[path(3)] ?? null, '%s '); ?>Jobs</h1>
  <?php if(!empty($jobs)) { ?>
    <ul>
      <?php foreach(array_reverse($jobs) as $job) { ?>
        <li><a href="<?= path("/jobs/{$job['id']}/"); ?>"><?= $job['title']; ?><?= $if($job['company'], null, ' (%s)'); ?></a><br /><?= $truncate($job['description']); ?></li>
      <?php } ?>
    </ul>
  <?php } else { ?>
    <p>No jobs have been created.</p>
  <?php } ?>
  <?php if($oauth->connected) { ?>
    <a href="<?= path('/jobs/create/'); ?>">Create Job</a>
  <?php } ?>
<?php } ?>
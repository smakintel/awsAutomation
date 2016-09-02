<?php
date_default_timezone_set('Asia/Singapore');


// list all snapshot ids in array
$describeSnaphots=shell_exec("aws ec2 describe-snapshots --owner-ids <ownerId here> --filters Name=status,Values=completed --region=<region-here>");


$test=  json_decode($describeSnaphots,TRUE);
//1 st snapshot
$test2= $test['Snapshots'][0]['Description'];

$descArray= array();
$snapshotIdArray =array();

$x=0;

foreach ($test as $elem=>$item) {
        foreach ($item as $value) {
                $descArray[$x]= $value['Description'];

                if ($value['Description']!=date('dmy')) {
                        $snapshotIdArray[$x]=$value['SnapshotId'];
                }


                $x++;
        }
}

//print_r($descArray);
//print_r($snapshotIdArray);

if (!in_array(date('dmy'),$descArray)) {
        echo "not found for today - create snapshot start \n";
        $createSnaphot=shell_exec("aws ec2 create-snapshot --volume-id <volumeID here> --description ".date('dmy') );
}

// delete snapshot
foreach ($snapshotIdArray as $snapId) {
        echo "\n start deleting snapshots not matching tody's date. Having snapshot ID =  ".$snapId. "\n";

       $deleteSnaphot=shell_exec("aws ec2 delete-snapshot --snapshot-id  ".$snapId );

}




?>

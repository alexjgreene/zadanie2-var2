<?php
$db = new PDO(
  "mysql:host=localhost;dbname=faculty;charset=utf8", 
  "root",
  ""
);

?>
<html>
	<body>
		<form method="GET" action="index.php">
			<?php
				$subjects = $db->query('
					SELECT * FROM `subject`
				')->fetchAll();
			?>
			<select name="subject">
				<?php foreach ($subjects as $subject) { ?>
				<option
					value="<?= htmlspecialchars($subject['id']) ?>"
					<?php
						if (
							isset($_GET['subject']) &&
							$_GET['subject'] == $subject['id']
						) {
							echo ' selected';
						}
					?>
				>
					<?= htmlspecialchars($subject['name']) ?>
				</option>
				<?php } ?>
			</select>
			<input type="submit" value="Найти">
		</form>
		<?php 
		if (isset($_GET['subject'])) { 
			$query = $db->prepare('
				SELECT DISTINCT `group`.`number` FROM `group`
				INNER JOIN `student` on `group`.`id` = `student`.`groupId`
				INNER JOIN `mark` on `mark`.`studentId` = `student`.`id`
				INNER JOIN `course` ON `mark`.`courseId` = `course`.`id`
				WHERE `course`.`subjectId` = :subject AND `mark`.`value` IS NOT NULL
			');
			$query->execute(['subject' => $_GET['subject']]);
			$groups = $query->fetchAll();
			if (count($groups) > 0) {
			?>
			<ul>
				<div>Номера групп:</div>
				<?php foreach ($groups as $group) { ?>
					<li> <?= htmlspecialchars($group['number']) ?></li>
				<?php } ?>
			</ul>
			<?php
			} else {
				?><div>Группы не найдены</div><?php
			}
		}
		?>
	</body>
</html>
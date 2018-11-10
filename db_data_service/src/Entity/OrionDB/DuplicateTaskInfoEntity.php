<?php

namespace DBService\Entity\OrionDB;

class DuplicateTaskInfoEntity
{
	private $id;

	private $taskName;

	private $userId;

	private $status;

	private $rootPath;

	private $jsonName;

	private $createTime;

	private $completeTime;

	private $description;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getTaskName()
	{
		return $this->taskName;
	}

	/**
	 * @param mixed $taskName
	 */
	public function setTaskName($taskName)
	{
		$this->taskName = $taskName;
	}

	/**
	 * @return mixed
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @param mixed $userId
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	public function getRootPath()
	{
		return $this->rootPath;
	}

	/**
	 * @param mixed $rootPath
	 */
	public function setRootPath($rootPath)
	{
		$this->rootPath = $rootPath;
	}

	/**
	 * @return mixed
	 */
	public function getJsonName()
	{
		return $this->jsonName;
	}

	/**
	 * @param mixed $jsonName
	 */
	public function setJsonName($jsonName)
	{
		$this->jsonName = $jsonName;
	}

	/**
	 * @return mixed
	 */
	public function getCreateTime()
	{
		return $this->createTime;
	}

	/**
	 * @param mixed $createTime
	 */
	public function setCreateTime($createTime)
	{
		$this->createTime = $createTime;
	}

	/**
	 * @return mixed
	 */
	public function getCompleteTime()
	{
		return $this->completeTime;
	}

	/**
	 * @param mixed $completeTime
	 */
	public function setCompleteTime($completeTime)
	{
		$this->completeTime = $completeTime;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}
}
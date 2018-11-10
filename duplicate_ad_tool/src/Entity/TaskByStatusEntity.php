<?php

namespace DuplicateAd\Entity;

class TaskByStatusEntity
{
	private $id;

	private $taskName;

	private $userId;

	private $rootPath;

	private $jsonName;

	private $createTime;

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
}
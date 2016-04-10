<?php
namespace Craft;

class UserSproutImportElementImporter extends BaseSproutImportElementImporter
{
	/**
	 * @return mixed
	 */
	public function getModel()
	{
		$model = 'Craft\\UserModel';

		return new $model;
	}

	/**
	 * @return bool
	 * @throws Exception
	 * @throws \Exception
	 */
	public function save()
	{
		return craft()->users->saveUser($this->model);
	}
}
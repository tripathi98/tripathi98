<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model 
 */
class UserRolesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_roles'); 
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		
		$this->belongsTo('Users',['foreignKey' => 'user_id']);
		$this->belongsTo('Roles',['foreignKey' => 'role_id']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('user_id') 
            ->requirePresence('user_id', 'create');
  
		$validator
            ->scalar('role_id') 
            ->requirePresence('role_id', 'create');
 

        return $validator;
    }
	
	public function validationAdduser(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');
  
		  
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 25)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');
 

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['phone']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
	
	public function findAuth(\Cake\ORM\Query $query, array $options)
	{
		return $query->where(
			[
				'OR' => [
					$this->aliasField('email') => $options['username'],
					$this->aliasField('phone') => $options['username'],
				]
			],
			[],
			true
		); 
		
	}
	
	 
}

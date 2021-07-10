<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

/**
 * Users Model 
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		
		$this->hasMany('UserRoles');
		
		$this->hasMany('Leaves');
		$this->hasMany('UserAccessdates');
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
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');
  
		$validator->requirePresence('password')
            ->notEmpty('password', 'please enter password.')
			->minLength('password', 8)
			->add('password',[
                    'matches'=> [
                        'rule' => function($value, $stuff) {
                                return $value === $stuff['data']['password'];
                            },
                        'message' => 'Password does not match.'
                    ]
                ]
            );

		$validator->requirePresence('confirm_password', 'please enter confirm password.')
            ->notEmpty('confirm_password', 'please enter confirm password.')
			->add('confirm_password',[
                    'matches'=> [
                        'rule' => function($value, $stuff) {
                                return $value === $stuff['data']['password'];
                            },
                        'message' => 'Password does not match.'
                    ]
                ]
            );

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');
			
		$validator		
			->add('email', 'unique', [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Email is already used'
             ]); 

        $validator
            ->scalar('phone')
            ->maxLength('phone', 25)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');
		
		$validator		
			->add('phone', 'unique', [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Phone is already used'
             ]);

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
			->add('email', 'unique', [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Email is already used'
             ]);  

        $validator
            ->scalar('phone')
            ->maxLength('phone', 25) 
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');
			
		$validator		
			->add('phone', 'unique', [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Phone is already used'
             ]);
			 
  
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
	/* public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['phone']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }  */  
	
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

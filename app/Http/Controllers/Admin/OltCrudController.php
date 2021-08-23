<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OltRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OltCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OltCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Olt::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/olt');
        CRUD::setEntityNameStrings('OLT', 'OLTs');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns
        CRUD::column('enabled')->type('check');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OltRequest::class);

        //CRUD::setFromDb(); // fields

        $this->crud->addFields([
            [
                'name' => 'nome',
                'attributes' => [
                        'placeholder' => 'Name of the OLT'
                    ]
            ],
            [
                'name' => 'ip',
                'attributes' => [
                        'placeholder' => 'IP of the OLT'
                    ]
            ],
            [
                'name' => 'port',
                'default' => '23',
                'attributes' => [
                        'placeholder' => 'Port of the OLT'
                    ]
            ],
            [
                'name' => 'user',
                'default' => 'admin',
                'attributes' => [
                        'placeholder' => 'The user for connection'
                    ]
            ],
            [
                'name' => 'pass',
                'label' => 'Password',
                'attributes' => [
                        'placeholder' => 'The password for connection'
                    ]
            ],
            [
                'name' => 'slot',
                'label' => 'Number of Slots',
                'default' => '4',
                'attributes' => [
                        'placeholder' => 'Number of OLT Slots'
                    ]
            ],
            [
                'name' => 'pon',
                'label' => 'Number of PONs',
                'default' => '16',
                'attributes' => [
                        'placeholder' => 'Number of PONs per Slot'
                    ]
            ],
            [
                'name' => 'vendor',
                'type' => 'select2_from_array',
                'options' => ['huawei' => 'Huawei', 'nokia' => 'Nokia', 'datacom' => 'Datacom']
            ],
            [
                'name' => 'enabled',
                'type' => 'checkbox',
                'default' => 1
            ]
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

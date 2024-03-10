<x-tomato-admin-container label="{{trans('tomato-admin::global.crud.edit')}} {{__('Refund')}} #{{$model->id}}">
    <x-splade-form class="flex flex-col space-y-4" action="{{route('admin.refunds.update', $model->id)}}" method="post" :default="$model">
        <x-tomato-search
            :label="__('Order')"
            :placeholder="__('Order')"
            name="order_id"
            remote-url="{{route('admin.refunds.orders')}}"
            remote-root="data"
            option-label="uuid"
            option-value="object"
            @change="
                form.company_id = form.order_id.company_id;
                form.branch_id = form.order_id.branch_id;
                form.price = form.order_id.total;
                form.discount = form.order_id.discount;
                form.vat = form.order_id.vat,
                form.items = form.order_id.items
            "
        />
        <div v-if="form.errors.order_id"
             class="text-danger-500 mt-2 text-xs font-chakra flex gap-2 mb-[6px]">
            <p v-text="form.errors.order_id"> </p>
        </div>


        <div v-if="form.items.length">
            <x-tomato-items :options="['item'=>'', 'price'=>0, 'discount'=>0, 'tax'=>0, 'qty'=>1,'total'=>0, 'options' =>(object)[]]" name="items">
                <div class="grid grid-cols-12 gap-4 border-b py-4 my-4">
                    <div class="col-span-6">
                        {{__('Item')}}
                    </div>
                    <div class="col-span-5">
                        {{__('QTY')}}
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-12 gap-4" v-for="(item, key) in items.main">
                        <div class="col-span-6 flex justify-between gap-4">
                            <x-tomato-search
                                @change="
                                    items.main[key].price = items.main[key].item?.price;
                                    items.main[key].discount = items.main[key].item?.discount;
                                    items.main[key].tax = items.main[key].item?.vat;
                                    items.updateTotal(key)
                                "
                                :remote-url="route('admin.orders.product')"
                                option-label="name?.{{app()->getLocale()}}"
                                remote-root="data"
                                v-model="items.main[key].item"
                                placeholder="{{__('Select Item')}}"
                                label="{{__('Product')}}"
                            />
                        </div>
                        <x-splade-input
                            class="col-span-5"
                            type="number"
                            placeholder="QTY"
                            v-model="items.main[key].qty"
                            @input="items.updateTotal(key)"
                        />
                        <button @click.prevent="items.removeItem(item)" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-danger-600 hover:bg-danger-500 focus:bg-danger-700 focus:ring-offset-danger-700 text-white border-transparent">
                            <i class="bx bx-trash"></i>
                        </button>
                        <div class="col-span-3" v-if="items.main[key].item.has_options" v-for="(option, optionIndex) in items.main[key].item.product_metas[0].value ?? []">
                            <div>
                                <label for="">
                                    @{{ optionIndex.charAt(0).toUpperCase() + optionIndex.slice(1) }}
                                </label>
                                <x-splade-select v-model="items.main[key].options[optionIndex]">
                                    <option v-for="(value, valueIndex) in option" :value="value">
                                        @{{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                    </option>
                                </x-splade-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Tax')}}
                        </div>
                        <div>
                            @{{ items.tax }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Sub Total')}}
                        </div>
                        <div>
                            @{{ items.price }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Discount')}}
                        </div>
                        <div>
                            @{{ items.discount }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Total')}}
                        </div>
                        <div>
                            @{{ items.total }}
                        </div>
                    </div>
                </div>
            </x-tomato-items>
        </div>

        <x-splade-select v-if="form.status !== 'inventory'" choices :label="__('Status')" name="status"  :placeholder="__('Status')">
            <option value="pending">{{__('Pending')}}</option>
            <option value="factory">{{__('Factory')}}</option>
            <option value="bad">{{__('Bad')}}</option>
            <option value="inventory">{{__('Inventory')}}</option>
        </x-splade-select>

        <x-splade-textarea :label="__('Notes')" name="notes" :placeholder="__('Notes')" autosize />

        <div class="flex justify-start gap-2 pt-3">
            <x-tomato-admin-submit  label="{{__('Save')}}" :spinner="true" />
            <x-tomato-admin-button danger :href="route('admin.refunds.destroy', $model->id)"
                                   confirm="{{trans('tomato-admin::global.crud.delete-confirm')}}"
                                   confirm-text="{{trans('tomato-admin::global.crud.delete-confirm-text')}}"
                                   confirm-button="{{trans('tomato-admin::global.crud.delete-confirm-button')}}"
                                   cancel-button="{{trans('tomato-admin::global.crud.delete-confirm-cancel-button')}}"
                                   method="delete"  label="{{__('Delete')}}" />
            <x-tomato-admin-button secondary :href="route('admin.refunds.index')" label="{{__('Cancel')}}"/>
        </div>
    </x-splade-form>
</x-tomato-admin-container>

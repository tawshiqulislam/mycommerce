import React, { useContext } from "react";
import FiltersSelected from "./FiltersSelected";
import { useForm, usePage } from "@inertiajs/react";
import FilterContainer from "./FilterContainer";
import FilterCheckbox from "./FilterCheckbox";
import { SearchContext } from "../Search";
import FilterPrice from "./FilterPrice";

const Filters = () => {
    const form = useContext(SearchContext);

    const { listDepartments, listCategories, listColors } = usePage().props;

    const changeFilterCheckbox = (filterName, optionsChecked) => {
        form.setData(filterName, optionsChecked);
    };

    const changeFilterAttributes = (attributeName, newAttributeValues) => {
        form.setData("attributes", {
            ...form.data.attributes,
            [attributeName]: newAttributeValues,
        });
    };

    return (
        <div className="divide-y divide-gray-200 ">
            <div className="pb-5">
                <FiltersSelected
                    data={form.data}
                    setData={form.setData}
                    changeFilterAttributes={changeFilterAttributes}
                    changeFilter={changeFilterCheckbox}
                />
            </div>
            <FilterContainer title="Departments">
                <FilterCheckbox
                    optionsList={listDepartments}
                    optionsChecked={form.data.departments || []}
                    changeFilterCheckbox={changeFilterCheckbox}
                    filterName="departments"
                />
            </FilterContainer>
            <FilterContainer title="Categories">
                <FilterCheckbox
                    optionsList={listCategories}
                    optionsChecked={form.data.categories || []}
                    changeFilterCheckbox={changeFilterCheckbox}
                    filterName="categories"
                />
            </FilterContainer>
            <FilterContainer title="Price">
                <FilterPrice data={form.data} setData={form.setData} />
            </FilterContainer>
        </div>
    );
};

export default Filters;

export const FilterTitle = ({ children }) => {
    return <h3 className="font-medium mb-4 ">{children}</h3>;
};

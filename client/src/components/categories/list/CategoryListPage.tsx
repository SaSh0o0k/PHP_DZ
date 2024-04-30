import { IconPencilPlus } from "@tabler/icons-react";
import {useDeleteCategoryMutation, useGetCategoriesQuery } from "../../../services/category.ts";
import {Button} from "../../ui/Button.tsx";
import {useState} from "react";
import Skeleton from "../../../helpers/Skeleton.tsx";
import CategoryCreateModal from "../create/CategoryCreateModal.tsx";
import CategoryEditModal from "../edit/CategoryEditModal.tsx";
import CategoryGrid from "./CategoryGrid.tsx";
import showToast from "../../../utils/showToast.ts";

const CategoryListPage = () => {

    const [createModalOpen, setCreateModalOpen] = useState<boolean>(false);
    const [editModalOpen, setEditModalOpen] = useState<boolean>(false);
    const [currentCategory, setCurrentCategory] = useState<number | null>(null);
    const { data, isLoading } = useGetCategoriesQuery();

    const [deleteCategory] = useDeleteCategoryMutation();

    const handleDeleteCategory = async (id: number) => {
        try {
            await deleteCategory(id).unwrap();
            showToast(`Category ${id} successful deleted!`, "success");
        } catch (err) {
            // eslint-disable-next-line @typescript-eslint/ban-ts-comment
            // @ts-expect-error
            showToast(`Error deleted ${id} category! ${err.error}`, "error");
        }
    };

    const handleEditCategory = async (id: number) => {
        try {
            setCurrentCategory(id);
            setEditModalOpen(true);
        } catch (err) {
            // eslint-disable-next-line @typescript-eslint/ban-ts-comment
            // @ts-expect-error
            showToast(`Error edited category! ${err.error}`, "error");
        }
    };

    return (
        <div>
            <div className="mb-3 flex flex-row-reverse">
                <Button variant="outlined" size="lg" onClick={() => setCreateModalOpen(true)}>
                    <IconPencilPlus/>
                    Add new category
                </Button>
            </div>

            {isLoading && <Skeleton/>}
            {/*{categories?.data?.map(category => (*/}
            {/*    <CategoryItem key={category.id} {...category} />*/}
            {/*))}*/}
            <CategoryGrid
                categories={categories?.data}
                totalPages={categories?.last_page}
                edit={handleEditCategory}
                remove={handleDeleteCategory}
                isLoading={isLoading}
            />

            {createModalOpen && <CategoryCreateModal open={createModalOpen} close={() => setCreateModalOpen(false)}/>}

            {editModalOpen && currentCategory && (
                <CategoryEditModal id={currentCategory} open={editModalOpen} close={() => setEditModalOpen(false)}/>
            )}
        </div>
    );
}

export default CategoryListPage;
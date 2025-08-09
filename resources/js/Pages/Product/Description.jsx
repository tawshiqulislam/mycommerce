import SectionTitle from "@/Components/Sections/SectionTitle";
import Specifications from "./Specifications";

const Description = ({ product }) => {
    return (
        <div>
            <div className="pt-content">
                <SectionTitle title="Description" />
                <div className="mt-5 text-justify">
                    <p
                        className="break-words "
                        dangerouslySetInnerHTML={{
                            __html: product.description,
                        }}
                    />
                </div>
            </div>
            {product.specifications && product.specifications.length > 0 && (
                <div className="pt-content">
                    <SectionTitle title="Specifications" />
                    <div className="mt-5">
                        <Specifications
                            specifications={product.specifications}
                        />
                    </div>
                </div>
            )}
        </div>
    );
};

export default Description;

const ProductCard = ({ product }) => {
    return (
        <div className="product-card">
            <img src={product.imageUrl} alt={product.name} />
            <h2>{product.name}</h2>
            <p>{product.description}</p>
            <p>${product.price}</p>
        </div>
    );
};

export default ProductCard;

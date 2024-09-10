const NumberHelper = (number) => {
    return number.startsWith('0') ? `62${number.slice(1)}` : number;
};

export default NumberHelper;

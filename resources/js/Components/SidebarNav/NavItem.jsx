import { Flex, Icon } from "@chakra-ui/react";
import { Link, usePage } from "@inertiajs/react";
import React from "react";

const NavItem = ({ icon, children, href, ...rest }) => {
    const { url } = usePage();
    const isActive = url === href || (href !== "/" && url.startsWith(href));

    return (
        <Link
            href={href}
            style={{ textDecoration: "none" }}
            _focus={{ boxShadow: "none" }}
        >
            <Flex
                align="center"
                py="2"
                px="4"
                mx="6"
                mb={2}
                borderRadius="lg"
                role="group"
                cursor="pointer"
                bg={isActive ? "green.700" : "transparent"}
                color={isActive ? "white" : "inherit"}
                _hover={{
                    bg: "green.700",
                    color: "white",
                }}
                {...rest}
            >
                {icon && (
                    <Icon
                        mr="4"
                        fontSize="16"
                        _groupHover={{
                            color: "white",
                        }}
                        as={icon}
                    />
                )}
                {children}
            </Flex>
        </Link>
    );
};

export default NavItem;

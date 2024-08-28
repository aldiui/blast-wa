import React, { useRef, useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { pickBy } from "lodash";
import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Flex,
    Heading,
    Icon,
    Input,
    Select,
    Text,
} from "@chakra-ui/react";
import Pagination from "../../../Components/Pagination";
import DataTable from "../../../Components/Datatable";
import { PlusIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout ";

const Kelas = ({ auth, sessions, kelas }) => {
    const perpage = useRef(kelas.per_page);
    const [isLoading, setIsLoading] = useState(false);
    const [search, setSearch] = useState("");

    const handleChangePerPage = (e) => {
        perpage.current = parseInt(e.target.value);
        getData();
    };

    const handleSearch = (e) => {
        e.preventDefault();
        getData();
    };

    const getData = () => {
        setIsLoading(true);
        router.get(
            "/kelas",
            pickBy({ perpage: perpage.current, search: search }),
            {
                onFinish: () => setIsLoading(false),
                preserveScroll: true,
                preserveState: true,
            }
        );
    };

    const calculateIndex = (index) => kelas.from + index;

    const columns = [
        { header: "#", accessor: null, width: "5" },
        { header: "Nama", accessor: "nama" },
        {
            header: "Aksi",
            accessor: "Aksi",
            width: "10",
            uri: "/kelas",
        },
    ];

    return (
        <AdminLayout auth={auth} sessions={sessions}>
            <Head title="Kelas" />
            <Card p={2} w="full" h={["auto", "full"]}>
                <CardHeader
                    display={"flex"}
                    justifyContent={"space-between"}
                    alignItems={"center"}
                >
                    <Heading size="md" fontWeight="bold">
                        Data Kelas
                    </Heading>
                    <Button
                        as={Link}
                        href="/kelas/create"
                        colorScheme="green"
                        size={"sm"}
                    >
                        <Icon as={PlusIcon} name="plus" mr={2} /> Tambah
                    </Button>
                </CardHeader>
                <CardBody>
                    <Flex
                        flexDir={{ base: "column", md: "row" }}
                        justifyContent={"space-between"}
                        alignItems={"center"}
                        gap={4}
                        mb={4}
                    >
                        <Select
                            id="perpage"
                            size={"sm"}
                            name="perpage"
                            width="auto"
                            value={perpage.current}
                            onChange={handleChangePerPage}
                        >
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </Select>
                        <Flex as={"form"} gap={2} onSubmit={handleSearch}>
                            <Input
                                size={"sm"}
                                id="search"
                                name="search"
                                type="text"
                                width="auto"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                            />
                            <Button
                                type="submit"
                                colorScheme="blue"
                                variant="outline"
                                size={"sm"}
                                isLoading={isLoading}
                                loadingText="Cari"
                            >
                                Cari
                            </Button>
                        </Flex>
                    </Flex>
                    <DataTable
                        columns={columns}
                        data={kelas.data}
                        isLoading={isLoading}
                        calculateIndex={calculateIndex}
                    />
                    <Flex
                        gap={4}
                        flexDir={{ base: "column", md: "row" }}
                        justifyContent={{ base: "center", md: "space-between" }}
                        alignItems="center"
                        mt={4}
                    >
                        <Text color="gray.500" fontSize="sm">
                            Showing {kelas.from ?? 0} to {kelas.to ?? 0} of{" "}
                            {kelas.total ?? 0}
                        </Text>
                        <Pagination links={kelas.links ?? []} />
                    </Flex>
                </CardBody>
            </Card>
        </AdminLayout>
    );
};

export default Kelas;
